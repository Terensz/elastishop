Amikor a <a href="[httpDomain]/documents/what-is-gdpr">GDPR</a>-t 2018-ban bevezették az EU-ban, a webfejlesztők zöme úgy gondolta, ez a rengeteg adatvédelmi megszorítás csak púp lesz a hátukon, eddig is milyen jól megvolt mindenki nélküle. Az ElastiSite megalkotója, vagyis én, akkor is, és most is örülök, hogy végre valakik megpróbálnak kompetenciát és minőséget kényszeríteni a programozókra. Kezdettől fogva azt a küldetést tűztem a zászlómra, hogy a legtrükkösebb hackereknek is ellenálló, ugyanakkor végtelenül gyors keretrendszert fejlesztek. Így két év után úgy néz ki, hogy ez tökéletesen sikerült.
<br><br>
Az internet egy darázsfészek, tele szárnypróbálgató és profi zsiványokkal és kópékkal. Vagy az ügyfeleink adatait akarják megszerezni hasznot remélve ebből, vagy a "feltörtem egy webes alkalmazást"-érzés kéjes örömét keresik.
<br><br>

<h2>Ezek a mechanizmusok védik az ElastiSite-alapú webes alkalmazásokat a feltöréstől:</h2>

<b>I.:</b> Felhasználói kérések teljes átvizsgálása
Az elsők között levő folyamat az összes felhasználó által elküldött adat és a feltöltött file-ok teljes átvizsgálása.
Ne legyenek illúzióink: a hacker akkor is tud adatot küldeni, ha azon az oldalon nincs is olyan mező, és akkor is tud file-t feltölteni, ha egyébként mi nem is csináltunk az egész webhelyen erre lehetőséget.
<br><br>
Az ElastiSite ezen a ponton csak megvizsgálja a beérkező kéréseket, hogy tartalmaznak-e feltörési kisérletet.
Minden feltörési kisérletet büntetőponttal jutalmaz, vagyis az első feltörési kisérlet nem jár azonnali tiltással. Természetesen ez csak akkor igaz, amennyiben azt feltételezhetjük, hogy a kezdő hacker, vagy informatikus-palánta böngészőn keresztül nézi az oldalunkat, vagyis szembesíteni tudjuk tettével. Létezhet olyan eset, amikor egyértelmű, hogy nem erről van szó, erre a IV-es pontban még visszatérek.
<br><br>
<b>II.:</b> Eddigi büntetőpontok összeszámolása: figyelmeztetés vagy tiltás
Még mielőtt az ElastiSite megállapítaná, hogy a felhasználó milyen oldalt szeretne meglátogatni, megvizsgálja, hogy rendeltetésszerűen használja, vagy épp fel szeretné törni a webes alkalmazást. És ha az utóbbi történik, kapott-e már figyelmeztetést. Ha még nem, akkor a támadót szembesíti az éppen az imént elkövetett tettével, és tájékoztatja annak súlyáról, az ismételt elkövetés következményéről és a felhasználók felelősségéről és a felhasználás feltételeiről.
<br><br>
<b>III.:</b> Az aktuális oldalon található kérdőív átvizsgálása, hogy érkezett-e olyan kérés, ami nem volt a kérdőíven.
(Pl. a bejelentkezési formon valószínűleg nincs cég neve mező, vagyis ha ilyen kérés érkezik, azt az ElastiSite érzékeli, adminisztrálja, és büntetőpontokkal "jutalmazza")
Nem szabad engedni, hogy olyan adatot is elküldhessen a felhasználó, amit amúgy nem is várunk, vagyis nincs vele dolgunk. Ezek a tevékenységek a webhelyünk, szerverünk, beállításaink sérülékenységeit keresik.
<br><br>
<b>IV.:</b> Ha nem volt kérdőív az oldalon, de mégis érkezett bármilyen kérés, azért azonnali tiltás jár. Ugyanis itt már nem kezdő hackerrel van dolgunk, és nem is böngészőn keresztül próbálja törni az oldalunkat.
Az ElastiSite ilyenkor azonnal e-mailt küld az Üzemeltetésnek, hogy a tűzfalon is tiltsa ki a hackert, ha a tűzfal esetleg nem lenne a helyzet magaslatán.
<br><br>
<h2>Az adatok védelmét szolgáló fejlesztési irányvonalak</h2>
Az ElastiSite megalkotásakor a legfontosabb tényező volt a felhasználói adatok védelme, ezért már az adatstruktúra és az adatkezelés is máshogyan került kialakításra, mint az ismertebb keretrendszereknél szokás, vagy a legtöbb fejlesztő fejében bevett szokásként rögzült megoldások.
<br><br>

<h2>Mik az ElastiSite indirekt biztonsági és adatvédelmi megoldásai?</h2>
<b>1.:</b> A felhasználók adatbázisban kerülnek tárolásra. A felhasználók, és csakis ők. Nincsenek jogosultságok tárolva, hiszen aki adatbázisban van, az mindenki felhasználó. Az "adminok" (vagyis a webhely üzemeltetői) egy nem átjárható adattárban tárolódnak, vagyis semmilyen furfangos módon nem lehet az adataikat megszerezni. Tehát ha pl. egy üzemeltetési hiba miatt (pl. hibás jogosultságok beállítása az ElastiSite mappáira) mégis feltörnék az adatbázist, a webhelyre nem tudnak maguknak olyan felhasználót gyártani, ami további károkat okozhatna.
<br><br>
<b>2.:</b> A személyes adatok függetlenítése a regisztrációs adatoktól
A személyes adatok külön tárolódnak a felhasználói fiók adataitól. Ha egy felhasználó kéri a személyes adatainak törlését, azok minden káros következmény nélkül szó szerint egy mozdulattal törölhetők az adatbázisból. 
Hiszen a regisztrációs alapadatok (azonosító, aktív-e, mikor regisztrálták) így sértetlenül megmaradhatnak és szerepelhetnek tovább a statisztikákban úgy, hogy a továbbiakban személyes adatok nem fognak hozzájuk kapcsolódni (név sem).
<br><br>
<b>3.:</b> A felhasználók minden személyes adata titkosítva tárolódik. A kulcs "el van ásva" a webszerveren, egy olyan elérési úton, ahová a hackerek nem látnak be.
<br><br>
<b>4.:</b> A felhasználó törölheti a saját személyes adatait a webszerverhez kapcsolódó adatbázisból, amennyiben nem történt megrendelése és nem készült számlája. 
<br><br>
<b>5.:</b> Ha bármilyen oknál fogva szándéka ellenére nem sikerül törölnie a személyes adatait a rendszerünkből annak ellenére, hogy nem volt adattörlést kizáró tényező (pl. volt már megrendelés, készült már számla),
<a class="ajaxCallerLink" href="[httpDomain]/[relativeContactUrl]">vegye fel velünk a kapcsolatot</a>!