# FordCheck
FordCheck è nato come un progetto per il tempo libero, si tratta di un'app che consente di vedere molte più informazioni sulla propria Ford, prese direttamente dai server di FordPass.

## Requisiti minimi
Per installare ed utilizzare FordCheck è necessario avere delle basi nell'utilizzo di PHP, HTML e JSON.
Lo script è scritto interamente in PHP e l'interfaccia grafica utiizza Jquery.
Pertanto, sarà necessario installare il software su un server web (privato, oppure un servizio di hosting).

## Come funziona?
Per far funzionare il software è necessario installare il tutto su un web server e proteggerne adeguatamente l'accesso.
Una volta copiati i file sul server, è necessario modificare il file *index.php*, compilando le proprie credenziali e il numero di telaio, indispensabili al funzionamento dello script.
Il software è stato progettato per funzionare su una vettura (Fiesta mk8) dotata anche di antifurto. Ciò significa che potrete ottenere anche dati inerenti all'inserimento dell'allarme (ad esempio, se chiudete la vettura con il chiavino anziché con il telecomando, le porte saranno bloccate ma l'antifurto disinserito).

## Perché FordCheck?
Utilizzo molto l'app per controllare la mia vettura da remoto. Tuttavia, sono un appassionato di sistemi e reti e non potevo trattenere la mia curiosità, così ho fatto ricerche in rete per capire il funzinamento di FordPass.
Con mia grande sorpresa il tutto è molto, ma molto semplice.
I dati forniti dal server sono molto più completi rispetto all'app originale. Troviamo per esempio:
- Informazioni sullo stato della rigenerazione del filtro antiparticolato (sulle vetture diesel), sullo stato del sistema ibrido ed ancora informazioni dettagliate per i veicoli elettrici, riguardo la ricarica;

![](https://i.imgur.com/GxyzPIA.png)

- Informazioni riguardo allo stato di portiere e finestrini (per i finestrini approssimativamente anche in che percentuale sono aperti), è possibile vedere quali porte sono aperte (inclusi cofano e portellone bagagliaio);

- Informazioni riguardo allo stato dell'accensione, quindi se il motore è acceso o meno

...ed altro ancora, come le coordinate geografiche della posizione dell'auto.

## Una mano?
Un aiuto è sempre ben accetto. Io non ne ho sentito la necessità, ma se qualcuno ha voglia di implementare dei pulsanti per aprire, chiudere ed accendere l'auto da remoto, magari con l'inserimento di una password, oppure magari di implementare e far visualizzare atri parametri e/o informazioni, ad esempio i parametri sulla rigenerazione o quelli riguardoa lla parte ibrida o alle batteria per le elettriche è possibile ed è il benvenuto.
Nei crediti, qui sotto, trovate il link da cui ho preso tutti i dettagli delle richieste, gli indirizzi dei server e tante spiegazioni sul funzionamento dei server FordPass.

## Credits
Questo progetto non sarebbe stato realizzato se non fossi stato messo sulla giusta strada da [questa discussione](https://www.reddit.com/r/shortcuts/comments/hmgxn5/fordpass_shortcuts/ "FordPAss Shortcuts - Reddit") su Reddit.
Da qui sarà possibile scaricare i comandi rapidi per controllare l'auto con Siri (avevo pubblicato un video sul gruppo "Ford Fiesta mk8 Italia" disponibile a [questo link](https://www.facebook.com/100000690900537/videos/4146208668745456/ "Aprire l'auto con Siri").

&copy; Luca d'Addabbo - 2021
