#include <iostream>
#include <fstream>
#include <sstream>
#include <string>
#include <vector>
#include <curl/curl.h>
#include <unistd.h> // Pour la fonction sleep
#include <cstdlib> // Pour la fonction system

// Fonction pour récupérer la valeur d'un fichier sysfs
std::string readValueFromFile(const std::string& filename) {
    std::ifstream file(filename);
    std::string value;
    if (file.is_open()) {
        std::getline(file, value);
        file.close();
    }
    return value.empty() ? "" : value; // Retourne une chaîne vide si le fichier est vide ou non ouvert
}

// Fonction pour obtenir l'espace disque utilisé
long long getDiskUsedSpace() {
    // Utiliser la commande df pour obtenir les informations sur les systèmes de fichiers montés
    std::string command = "df --output=used / | tail -n 1"; // Utiliser le point de montage racine "/"
    FILE* pipe = popen(command.c_str(), "r");
    if (!pipe) return -1;
    char buffer[128];
    std::string result = "";
    while (!feof(pipe)) {
        if (fgets(buffer, 128, pipe) != NULL)
            result += buffer;
    }
    pclose(pipe);

    // Convertir la sortie de la commande en entier
    long long usedSpace = std::stoll(result);
    return usedSpace;
}

// Fonction pour envoyer les données via une requête POST avec cURL
void sendPostRequest(float cpuTemp, int ramFree, int ramUsed, long long diskUsed, long long diskTotal) {
    // Initialisation de cURL
    CURL *curl = curl_easy_init();
    if (curl) {
        // Définition de l'URL de destination
        curl_easy_setopt(curl, CURLOPT_URL, "https://apimaster.flixmail.fr/server-update");

        // Création de la chaîne de données Form-Data
        std::ostringstream postDataStream;
        postDataStream << "cputmp=" << cpuTemp << "&ramfree=" << ramFree << "&ramused=" << ramUsed << "&diskused=" << diskUsed << "&disktotal=" << diskTotal;
        std::string postData = postDataStream.str();

        // Définition des options pour la requête POST
        curl_easy_setopt(curl, CURLOPT_POSTFIELDS, postData.c_str());
        curl_easy_setopt(curl, CURLOPT_FOLLOWLOCATION, 1L);
        curl_easy_setopt(curl, CURLOPT_DEFAULT_PROTOCOL, "https");

        // Exécution de la requête POST
        CURLcode res = curl_easy_perform(curl);
        if (res != CURLE_OK) {
            std::cerr << "Erreur lors de l'envoi de la requête POST : " << curl_easy_strerror(res) << std::endl;
        }

        // Nettoyage
        curl_easy_cleanup(curl);
    }
}

int main() {
    while (true) {
        // Récupération de la température du CPU
        std::string cpuTempPath = "/sys/class/thermal/thermal_zone0/temp";
        std::string cpuTempStr = readValueFromFile(cpuTempPath);
        float cpuTemp = 0.0f;
        try {
            if (!cpuTempStr.empty()) {
                cpuTemp = std::stof(cpuTempStr) / 1000.0f; // Conversion en degrés Celsius
            }
        } catch (const std::invalid_argument& e) {
            std::cerr << "Erreur de conversion : " << e.what() << std::endl;
            std::cerr << "Chaîne problématique : " << cpuTempStr << std::endl;
        }

        // Récupération de la mémoire RAM disponible
        std::string memInfoPath = "/proc/meminfo";
        std::ifstream memInfoFile(memInfoPath);
        std::string line;
        int total = 0, free = 0, used = 0;
        while (std::getline(memInfoFile, line)) {
            if (line.find("MemTotal:") != std::string::npos) {
                std::istringstream iss(line.substr(10));
                iss >> total;
            } else if (line.find("MemFree:") != std::string::npos) {
                std::istringstream iss(line.substr(9));
                iss >> free;
            } else if (line.find("MemAvailable:") != std::string::npos) {
                std::istringstream iss(line.substr(14));
                iss >> used;
            }
        }
        memInfoFile.close();

        // Récupération de l'espace disque total
        std::string diskSpacePath = "/sys/block/mmcblk0/size";
        std::string diskSpaceStr = readValueFromFile(diskSpacePath);
        long long diskSpace = 0;
        if (!diskSpaceStr.empty()) {
            diskSpace = std::stoll(diskSpaceStr) * 512 / (1024 * 1024 * 1024); // Conversion en Go
        }

        // Récupération de l'espace disque utilisé
        long long diskUsedSpace = getDiskUsedSpace();

        // Envoi des données via une requête POST
        sendPostRequest(cpuTemp, free, used, diskUsedSpace, diskSpace);

        // Attente de 25 minutes avant la prochaine exécution
        sleep(1500); // 1500 secondes = 25 minutes
    }

    return 0;
}