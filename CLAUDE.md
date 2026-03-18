# Anleitung für Claude zur tva Duplicate Pro Plugin-Versionierung

## Überblick

Dies ist eine Anleitung zur Arbeit mit dem tva Duplicate Pro WordPress-Plugin. Das Plugin ermöglicht die einfache Duplizierung von Beiträgen, Seiten und WooCommerce-Produkten mit nur einem Klick.

## Struktur des Projekts

Das Projekt ist nach Versionen strukturiert:

```
/tva-duplicate-pro/
  ├── CLAUDE.md (diese Datei)
  ├── INDEX.md (Übersicht aller Versionen)
  ├── current-version.md (Informationen zur aktuellen Version)
  ├── versions/
  │   ├── 2.1/
  │   │   ├── tva-duplicate-pro.php (Hauptdatei)
  │   │   ├── assets/ (CSS, JS und Bilder)
  │   │   ├── includes/ (PHP-Klassen und Funktionen)
  │   │   ├── README.md (Allgemeine Infos)
  │   │   ├── readme.txt (WordPress-Repository Format)
  │   │   ├── CHANGELOG.md (Änderungsprotokoll)
  │   │   ├── LICENSE (Lizenzinformationen)
  │   │   ├── CONTRIBUTING.md (Informationen für Mitwirkende)
  │   │   ├── INDEX.md (Versionsdetails)
  │   │   └── dist/tva-duplicate-pro.zip (Installationspaket)
  │   └── [zukünftige Versionen]
  └── [andere Dateien]
```

## Prozess für die Erstellung einer neuen Version

Wenn du an einer neuen Version des Plugins arbeiten sollst, befolge diese Schritte:

1. **Prüfe die aktuelle Version**:
   - Sieh in `current-version.md` nach, welche Version aktuell ist
   - Stelle sicher, dass du die Dateien aus dem entsprechenden Versionsordner verwendest

2. **Erstelle einen neuen Versionsordner**:
   ```bash
   mkdir -p "/Users/giampierosirianni/Documents/Claude/Wordpress Plugins/tva-duplicate-pro/versions/[neue-versionsnummer]"
   ```

3. **Kopiere die Dateien der vorherigen Version**:
   ```bash
   cp -R /Users/giampierosirianni/Documents/Claude/Wordpress\ Plugins/tva-duplicate-pro/versions/[aktuelle-version]/* /Users/giampierosirianni/Documents/Claude/Wordpress\ Plugins/tva-duplicate-pro/versions/[neue-versionsnummer]/
   ```

4. **Aktualisiere die Versionsnummer** in:
   - `tva-duplicate-pro.php` (Plugin-Header und Klassenvariable)
   - `readme.txt` (Stable tag)
   - `INDEX.md` im neuen Versionsordner
   - `CHANGELOG.md`

5. **Implementiere die Änderungen** für die neue Version

6. **Erstelle neue ZIP-Dateien**:
   ```bash
   cd "/Users/giampierosirianni/Documents/Claude/Wordpress Plugins/tva-duplicate-pro/versions/[neue-versionsnummer]"
   # ZIP für dist-Ordner (ohne unnötige Dateien)
   mkdir -p dist
   zip -r tva-duplicate-pro.zip tva-duplicate-pro.php README.md readme.txt CHANGELOG.md LICENSE CONTRIBUTING.md assets includes -x "*.DS_Store" -x "._*" -x ".git/*"
   # Eine Kopie im dist-Ordner speichern
   cp tva-duplicate-pro.zip dist/
   ```

7. **Aktualisiere die Hauptdateien**:
   - `INDEX.md`: Füge die neue Version zur Liste hinzu
   - `current-version.md`: Aktualisiere Versionsnummer und Datum

## Semantische Versionierung

Das Plugin verwendet semantische Versionierung (MAJOR.MINOR.PATCH):

- **MAJOR**: Inkompatible API-Änderungen
- **MINOR**: Funktionserweiterungen (abwärtskompatibel)
- **PATCH**: Bugfixes (abwärtskompatibel)

## Wichtige Dateien und ihre Funktionen

- **tva-duplicate-pro.php**: Hauptdatei mit Plugin-Logik
- **assets/**: CSS, JS und Bilder (wenn vorhanden)
- **includes/**: PHP-Klassen und Funktionen (wenn vorhanden)
- **README.md**: Übersicht für GitHub/GitLab
- **readme.txt**: WordPress-Plugin-Repository Format
- **CHANGELOG.md**: Chronik aller Änderungen
- **LICENSE**: Lizenzinformationen
- **CONTRIBUTING.md**: Informationen für Mitwirkende

## Notizen für die Entwicklung

1. Das Plugin benutzt eine Singleton-Klasse für die Hauptfunktionalität.
2. Das Plugin unterstützt WooCommerce-Produkte mit spezieller Behandlung für Bilder und SKUs.
3. Alle Änderungen sollten im CHANGELOG.md dokumentiert werden.
4. Der Name der ZIP-Datei MUSS immer "tva-duplicate-pro.zip" sein, damit WordPress das Plugin korrekt aktualisieren kann.
5. Jeder Versionsordner muss seine eigene ZIP-Datei enthalten, im Hauptordner wird KEINE aktuelle ZIP-Datei benötigt.
6. Die ZIP-Datei sollte sowohl direkt im Versionsordner als auch im "dist"-Unterordner gespeichert werden.
7. Der Download-Link in der current-version.md sollte direkt auf die ZIP-Datei im entsprechenden Versionsordner verweisen.

## Kontakt

Bei Fragen oder Unklarheiten wende dich an den Benutzer oder kontaktiere:
- Website: https://www.tva.sg