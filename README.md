<p align="center">
  <strong>RIABreast backend</strong><br>
  <em>Web & PWA per la riabilitazione post-mastectomia</em>
</p>

---

## 📋 Descrizione

RIABreast è un’applicazione web e mobile (Progressive Web App) dedicata alla riabilitazione post-operatoria per donne mastectomizzate.  
Permette alle pazienti di:

- Seguire un percorso guidato in video-lezioni.
- Registrare i progressi giornalieri.
- Compilare un questionario di autovalutazione per monitorare l’evoluzione e indirizzare alla fase riabilitativa successiva.

---

## ⚙️ Caratteristiche principali

1. **Registrazione anonima**  
   – 4 domande iniziali (età, tipo intervento, tempo trascorso, grado di movimento)  
   – Generazione di un PIN a 6 cifre hashato in DB
2. **Login con PIN**  
   – Autenticazione via token Bearer
3. **Homepage**  
   – Visualizzazione del periodo riabilitativo corrente  
   – Conteggio delle sessioni giornaliere
4. **Dettaglio periodo**  
   – Video di esercizi YouTube embeddato  
   – Popup di conferma avvio sessione
5. **Questionario di autovalutazione**  
   – Domande a scelta multipla o scala (1–10)  
   – Salvataggio delle risposte legate alla sessione

---

## 🛠️ Stack Tecnologico

- **Backend**: Laravel 11 (PHP 8.2)
- **Frontend**: Vue.js 3 + Vite, jQuery
- **Database**: MySQL
- **Stili**: SCSS modulare, mobile-first
- **API**: RESTful con autenticazione Bearer

---

## 🚀 Installazione e avvio in locale

> **Requisiti**
> - PHP ≥ 8.2, Composer
> - Node.js & npm (o Yarn)
> - MySQL

1. **Clona il repository**
   ```bash
   git clone https://github.com/tuo-username/breastapp.git
   cd breastapp
