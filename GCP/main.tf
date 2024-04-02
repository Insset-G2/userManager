provider "google" {
  project = "CCM-2024"
  region  = "europe-west9"
}

resource "google_compute_instance" "instance" {
  name         = "instance-server-node"
  machine_type = "e2-small"
  zone         = "europe-west9-b"
  
  boot_disk {
    initialize_params {
      image = "debian-cloud/debian-12"
    }
  }

  network_interface {
    network = "default"
    access_config {
      # Adresse IP externe statique
      nat_ip = "your_static_ip_address"
    }
  }

  tags = ["http-server", "https-server"]

  metadata = {
    # Activer SSH
    ssh-keys = "your_ssh_key"
  }

  metadata_startup_script = <<-SCRIPT
    #!/bin/bash
    # Installer Node.js version 18
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
    apt-get install -y nodejs
  SCRIPT

  scheduling {
    preemptible  = false
    on_host_maintenance = "MIGRATE"
    automatic_restart = true
  }
}
