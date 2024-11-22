## Voraussetzungen
- Docker und Docker-Compose installiert.
- DBeaver installiert.

## Installation und Start
1. Starte die Container innerhalb des Ordners, in dem sich die docker-compose.yml befindet:
    ```bash
   docker-compose up -d
2. Verbindung mit Datenbank in DBeaver herstellen:
- DBeaver öffnen
- In Menüleiste Symbol anklicken "Neue Verbindung"
- MySQL als Datenbanktyp wählen
3. Verbindungsdaten eintragen:
- URL: 
    ```bash
    jdbc:mysql://localhost:3306/booknook?allowPublicKeyRetrieval=true
- Benutzername: user
- Passwort: password
- Datenbankname: booknook

Klicke auf "Verbindung testen" ggf. Treiberdateien herunterladen, anschließend auf fertigstellen

3. SQL-Skript in Datenbank booknook ausführen:
- sql-Skript aus controller/setup.sql kopieren, in DBeaver einfügen und ausführen, um Tabellen zu erstellen
4. Öffne die Webanwendung im Browser:
    ```bash
   http://localhost:8080