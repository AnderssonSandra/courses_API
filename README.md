# courses_API
Backend for courses 

## Länk till API

http://studenter.miun.se/~saan1906/writeable/dt173g/backend/api.php

## Beskrivning av mitt API

### Klassen Databas

Jag har gjort min databas-koppling med PDO. Jag skapade en klass för databasen innehållande inställningar och funktionalitet för anslutning med hjälp av PDO samt funktionalitet för att avsluta anslutningen. 

### Klassen Courses

Utöver klassen för databas skapade jag en klass för kurser. I klassen gör jag en databas-anslutning. Jag har en konstruktor och så har jag funktionalitet för att hämta alla kurser, hämta en kurs, lägga till en kurs, uppdatera en kurs samt radera en kurs. 

### API
Jag har en fil API som jag använder som min webbtjänst. Jag hämtar in de övriga filerna hit, det vill säga de som innehåller klassen "courses" samt klassen "database". Jag skapar nytt objekt av databasen och gör en connection. Jag skapar sedan en instans av klassen kurser och skickar med databas-anslutningen som parameter. 

Jag har en swish metod som använder olika funktionalitet beroende på vilken metod som har skickats in. 
"GET": Om det är "GET" så görs en koll om det är ett id medskickat och då anropar den funktionen "getOne" för att hämta 1 kurs. Om inget id är medskickat anropas funktionen "getAll" som hämtar alla kurser. 
"POST": Om det är "POST" som metod så  görs det först en koll att det finns data medskickat och om det gör det så skickas den data med till funktionen "create" som uppdaterar databasen med den nya kursen. 
"DELETE": Om det är en "DELETE" metod så görs en koll om ett id har skickat med och då anropas funktionen "delete" och id: på kursen som ska raderas skickas med. Om inget id finns skickas ett felmeddelande tillbaka.
"PUT": Om det är en "PUT" metod så görs det en koll om ett id är medskicakt och om ett id finns så anropas funktionen "update" och ett id skickas med så att vi vet vilken kurs som ska updateras. om inget id är medskickat skickas ett felmeddelande.

Allt returneras i JSON format.
