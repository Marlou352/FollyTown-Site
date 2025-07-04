@echo off
echo Démarrage du serveur XAMPP Apache...
"C:\xampp\xampp_start.exe"

timeout /t 10 /nobreak

echo Lancement de ngrok sur le port 80...
start ngrok http 80

pause
