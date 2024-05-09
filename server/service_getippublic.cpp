#include <iostream>
#include <string>
#include <fstream>
#include <curl/curl.h>
#include <unistd.h>

// Déclaration de la fonction WriteCallback si nécessaire
size_t WriteCallback(void *ptr, size_t size, size_t nmemb, std::string *data);

// URL où vous envoyez la requête POST avec l'adresse IP actuelle
const std::string URL = "https://apimaster.flixmail.fr/ip-public-update";
const std::string LOG_FILE = "./post_log.txt";

// Fonction pour récupérer l'adresse IP publique du routeur
std::string get_public_ip()
{
    CURL *curl;
    CURLcode res;
    std::string response;

    curl = curl_easy_init();
    if (curl)
    {
        curl_easy_setopt(curl, CURLOPT_URL, "https://ifconfig.me/ip");
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, WriteCallback);
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, &response);
        res = curl_easy_perform(curl);
        curl_easy_cleanup(curl);
    }

    return response;
}

// Fonction principale du programme
int main()
{
    std::string current_ip;
    std::ofstream logfile(LOG_FILE, std::ios::app); // Ouverture du fichier de journalisation en mode ajout

    while (true)
    {
        try
        {
            std::string new_ip = get_public_ip();
            if (new_ip != current_ip)
            {
                CURL *curl;
                CURLcode res;

                curl = curl_easy_init();
                if (curl)
                {
                    // Création de la chaîne de données POST
                    std::string post_data = "ipaddress=" + new_ip;

                    curl_easy_setopt(curl, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_easy_setopt(curl, CURLOPT_URL, URL.c_str());
                    curl_easy_setopt(curl, CURLOPT_POSTFIELDS, post_data.c_str());
                    curl_easy_setopt(curl, CURLOPT_FOLLOWLOCATION, 1L);
                    curl_easy_setopt(curl, CURLOPT_DEFAULT_PROTOCOL, "https");
                    struct curl_slist *headers = NULL;
                    curl_easy_setopt(curl, CURLOPT_HTTPHEADER, headers);
                    res = curl_easy_perform(curl);
                    if (res == CURLE_OK)
                    {
                        logfile << "POST envoyé avec succès pour l'adresse IP : " << new_ip << std::endl;
                    }
                    else
                    {
                        logfile << "Échec de l'envoi POST pour l'adresse IP : " << new_ip << ". Erreur : " << curl_easy_strerror(res) << std::endl;
                    }
                    curl_easy_cleanup(curl);
                }

                current_ip = new_ip;
            }
        }
        catch (const std::exception &e)
        {
            std::cerr << "Erreur : " << e.what() << std::endl;
            logfile << "Erreur : " << e.what() << std::endl;
        }

        // Attendre 30 minutes avant de vérifier à nouveau
        sleep(1800);
    }

    // Fermeture du fichier de journalisation
    logfile.close();

    return 0;
}

// Définition de la fonction WriteCallback si nécessaire
size_t WriteCallback(void *ptr, size_t size, size_t nmemb, std::string *data)
{
    data->append((char *)ptr, size * nmemb);
    return size * nmemb;
}