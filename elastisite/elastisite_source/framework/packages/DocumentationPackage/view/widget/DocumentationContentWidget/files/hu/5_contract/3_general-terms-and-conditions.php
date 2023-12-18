<p>
Ez a dokumentum (továbbiakban: ÁSZF) minden szoftverfejlesztési szerződés része, ami létrejön <b><?php echo App::getContainer()->getCompanyData('name'); ?></b> és a tőle szoftverfejlesztést megrendelő magánszemély vagy szervezet között.
Az ÁSZF hatálya kiterjed minden olyan szerződésre, és csakis azokra a szerződésekre, amelyekben le van írva, hogy az ÁSZF rájuk kiterjed. Ezekben az esetekben a webes alkalmazás elkészítője az ÁSZF-et köteles a szerződő fél rendelkezésére 
bocsátani. Az ÁSZF-et a szerződéssel együtt mindkét fél köteles a másik fél számára aláírni.
</p>
<br>

<u><b>1.: A szerződő felek</b></u>
<p>
<b>a.:</b> Az a szerződés, aminek jelen dokumentum képezi a minden körülmények között érvényes feltételeit (a továbbiakban: Szerződés), két fél között jön létre: a szoftver megrendelője és a webes alkalmazás elkészítéséért felelős informatikus.<br>
A szoftver megrendelőjére a későbbiekben így fog hivatkozni ez a dokumentum: <b>Megrendelő</b>.<br>
A webes alkalmazás elkészítéséért felelős informatikusra a későbbiekben így fog hivatkozni ez a dokumentum: <b>Webfejlesztő</b>.
</p>
<br>

<u><b>2.: A szerződés tárgya</b></u>
<p>
<b>a.:</b> A szerződés tárgya a szerződésben "A szerződés tárgya"-ként megjelölt internetes szoftver (a továbbiakban Szoftver), vagy a Szoftverhez kapcsolódó support szolgáltatás. A szoftvernek minden funkciót tartalmaznia kell,
ami a Szoftver specifikációjában, vagy annak kiegészítésében szerepel.
</p>
<p>
<b>b.:</b> A Webfejlesztő nem köteles semmilyen funkciót implementálni a Szoftverbe, ami a Szoftver specifikációjában, vagy annak kiegészítésében nem szerepel.
</p>
<p>
<b>c.:</b> A Szoftver átadáskor semmilyen tartalmat, médiát és terméket nem tartalmaz, ezeknek a feltöltését a Webfejlesztő nem vállalja, és erre nem is kötelezhető.
</p>
<p>
<b>d.:</b> A Webfejlesztő nem felel a Megrendelő által feltöltött tartalomért, médiákért, termékekért. Ezeknek a felelősségét a Megrendelő viseli.
</p>
<br>

<u><b>3.: A szerződés szolgáltatóoldali műszaki feltételei</b></u>
<p>
<b>a.:</b> Minden szoftver és szolgáltatás, amelynek a szerződését jelen ÁSZF kiegészíti, csak abban az esetben képes működni, ha a szerződés tárgyát képező szoftver, vagy az ahhoz tartozó szolgáltatás üzemelési feltételei adottak.
</p>
<p>
<b>b.:</b>  A Szoftver egy felhasználók által elérhető speciális futtatókörnyezetet tartalmazó számítógépen, úgynevezett webszerveren (a továbbiakban: Webszerver) fut.
</p>
<p>
<b>c.:</b>  a Webszerver szolgáltatója (továbbiakban: Hosting szolgáltató) a Webszerveren biztosítja az alábbi technikai feltételeket:<br>
- Apache 2.4 webszerver-szoftver<br>
- PHP 7.4 scriptnyelv a következő telepített modulokkal: bcmath, bz2, calendar, Core, ctype, curl, date, dba, dom, exif, FFI, fileinfo, filter, ftp, gd, gettext, gmp, hash, iconv, intl, json, ldap, libxml, mbstring, 
mysqli, mysqlnd, odbc, openssl, pcntl, pcre, PDO, pdo_dblib, pdo_mysql, PDO_ODBC, pgsql, Phar, phpdbg_webhelper, posix, pspell, readline, Reflection, session, shmop, SimpleXML, soap, sockets, 
sodium, SPL, sqlite3, standard, sysvmsg, sysvsem, sysvshm, tidy, tokenizer, xml, xmlreader, xmlrpc, xmlwriter, xsl, Zend OPcache, zip, zlib<br>
- MySQL 7 vagy 8 <br>
- A szerződésben foglalt domain név regisztrációja<br>
- Domain névszerver összekapcsolása a Webszerverrel<br>
- SSL tanusítvány beállítása és összekapcsolása a Webszerverrel<br>
- Tűzfal üzemeltetése, amely legfeljebb 100 kérést engedélyez percenként IP-címenként a szoftver felé, az ezt a kérésszámot meghaladó felhasználókat pedig rosszindulatúnak nyilvánítja, és megakadályozza részükről a további kéréseket.
</p>
<p>
<b>d.:</b> A Webfejlesztő nem tartozik felelősséggel az általa fejlesztett és/vagy támogatott szoftverért, amelynek bármely szolgáltatójával bármilyen okból kifolyólag felbomlott a Megrendelő olyan szerződése, amely a webhely üzemeltetéséhez nélkülözhetetlen.
</p><br>

<u><b>4.: A Szoftver működésének felhasználóoldali műszaki feltételei</b></u>
<p>
<b>a.:</b> A Szoftver a futtatása közben a felhasználói oldalon kizárólag aktív, megfelelően működő internetkapcsolat esetén egy úgynevezett böngészőprogrammal (a továbbiakban: Böngésző) egy számítógép képernyőjén, 
vagy tabletkészüléken, vagy mobiltelefonon jeleníthető meg. A felhasználó internetkapcsolatának meglétéért és minőségéért a Webfejlesztő nem felel.
<p>
<b>b.:</b> A Webfejlesztő nem vállal felelősséget az 5/a. pontban leírtaktól eltérő készülékfajtákon (pl. okostelevízió képernyőjén) való megjelenítésért.
</p>
<p>
<b>c.:</b> A Webfejlesztő a készüléken futó operációs rendszerek függvényében határozza meg a támogatott Böngészők listáját, így a lista az operációs rendszer és böngésző nevét együttesen adja meg. <br>
- iOS 15.1 (iPhone és iPad) / Safari<br>
- iOS 15.1 (iPhone és iPad) / Chrome<br>
- iOS 15.2 (iPhone és iPad) / Safari<br>
- iOS 15.2 (iPhone és iPad) / Chrome<br>
- MacOS Monterey (Macbook és asztali Mac) / Safari v:15.1<br>
- MacOS Monterey (Macbook és asztali Mac) / Chrome v:96<br>
- Windows 10 / Edge<br>
- Windows 10 / Internet Explorer<br>
- Windows 10 / Chrome<br>
- Windows 10 / Firefox<br>
- Windows 11 / Edge<br>
- Windows 11 / Internet Explorer<br>
- Windows 11 / Chrome<br>
- Windows 11 / Firefox<br>
- Android 9.x / Chrome<br>
</p>
<p>
<b>d.:</b> A Webfejlesztő nem vállal azért felelősséget, ha a Böngészőt futtató készülék nem kapcsolódik az internetre, vagy ha a Böngésző nem megfelelően működik, vagy ha azt a felhasználó a gyári beállításokhoz képest elállította.
</p><br>

<u><b>5.: A felhasználás korlátozása</b></u><br>
<p>
<b>a.:</b> A Szoftver természetéből fakadóan a világ bármely olyan pontjáról elérhető, ahol korlátozás nélkül hozzáférhető az internet, kivéve azokat a helyeket, amelyekről a Webfejlesztő tiltja vagy korlátozza a Szoftver elérését.
</p>
<p>
<b>b.:</b> A Szoftver csak korlátozott formájában érhető el a Kínai Népköztérsaság és a Koreai Népi Demokratikus Köztársaság területéről. Ha egy felhasználó a korlátozás alá eső országok valamelyikéből szeretné elindítani a 
Szoftverhez tartozó bármely lapot, arról kap tájékoztatást, hogy a webes alkalmazás átmenetileg nem elérhető.
</p>
<p>
<b>c.:</b> A Szoftver nem elérhető azon felhasználók számára, akik a Felhasználási Feltételeket több alkalommal súlyosan megsértették, és emiatt a hozzáférésük korlátozásra került.
</p>
<p>
<b>d.:</b> Amennyiben a Megrendelő a c. pont szerint korlátozásra került, úgy a beüzemeléskor küldött levélben szereplő megfelelő linkre kattintva tudja a Webfejlesztő segítsége nélkül a tiltást megszüntetni.
</p>
<br>

<u><b>6.: A Szoftver átadása</b></u><br>
<p>
<b>a.:</b> Az elkészült szoftver definíciója:<br>
A szoftver akkor nevezhető elkészültnek, ha az alábbi feltételek egyidejűleg teljesülnek:<br>
    - A Szoftver a specifikációjában foglalt minden egyes funkcióját hiánytalanul tartalmazza, és a Webfejlesztő bemutatta a Megrendelőnek, hogy azok hibátlanul működnek. <br>
    - Ha a szerződés szerint a Webfejlesztő felel a beüzemelésért, és a beüzemelés megtörtént, egyéb esetben a szerződésben foglaltak szerinti átadás megtörtént.
    - A Megrendelő az átadást követő 48 órában nem emelt kifogást a Szoftver minőségét illetően.
</p>
<p>
<b>b.:</b> Az elkészült Szoftver vételárát a Megrendelő köteles haladéktalanul a Webfejlesztő rendelkezésére bocsátani.
</p>
<br>

<u><b>6.: Hibák elhárítása</b></u>
<p>
<b>a.:</b> Ha a Megrendelő azt tapasztalja, hogy a szerződés tárgyaként használt Szoftver nem elérhető vagy nem működik, úgy az alábbi lépéseket kell megtennie, mielőtt a hibát bejelentené a Webfejlesztőnél:<br>
- Ellenőriznie kell, hogy az az eszköz, amelyen a Szoftver nem volt elérhető, rendelkezik-e megfelelően működő internetkapcsolattal. A 4-es pontban foglaltak szerint a megfelelő internetkapcsolat megléte a Szoftver működésének az egyik alapfeltétele.<br>
- Ha van jó minőségű (nem szakadozó, nem belassult) internetkapcsolat, ellenőriznie kell, hogy más webhelyek elérhetők-e, és működnek-e ugyanezen a készüléken.<br>
- Amennyiben más webhelyek működnek, ellenőriznie kell, hogy a Webfejlesztő részére kapcsolattartóként megadott e-mail fiókját, hogy jött-e tájékoztatás a szolgáltatás elérhetetlenségéről.<br>
</p>
<p>
<b>b.:</b> Amennyiben a Megrendelő rendelkezik Support szolgáltatással, a hiba elhárításának érdekében fel kell vennie a kapcsolatot Webfejlesztővel. 
</p>
<p>
<b>c.:</b> Amennyiben a b. pontban szereplő feltételek egyike sem áll fenn, a Megrendelőnek a hosting-szolgáltatónál kell kivizsgálást kérnie.
</p>
<br>

<u><b>7.: Tartalmak, amik nem kerülhetnek fel semmilyen ElastiSite oldalra</b></u>
<p>
    A webfejlesztő tiltja bizonyos típusú tartalmak feltöltését. A tiltott tartalom sem újságcikk, sem médiatartalom, sem termék formájában nem kerülhet feltöltésre.<br>
    - Bármilyen kannabisz-származék<br>
    - Bármilyen termék, amelyet a Magyar Köztársaság vagy az Európai Unió bármely törvénye tilt<br>
    - Bármilyen terrorizmus mellett kiálló, azt pozitív dologként bemutató tartalom<br>
    - Bármilyen tartalom, mely bárkit megsért vallásában, szexuális irányultságában vagy politikai nézeteiben<br>
    - Bármilyen politikai propagandaanyag<br>
    - Bármilyen kis- vagy fiatalkorúakat tartalmazó, vagy róluk szóló erotikus képi-, hang-, vagy írásos anyag
</p><br>

<u><b>8.: Harmadik személynek átadott sütik (Third-party cookie)</b></u>
<p>
    A Webfejlesztő kérésre sem építi bele a szoftverbe a felhasználó adatait harmadik félnek átadó sütiket. Ez egy teljesen etikátlan cselekedet, ami a felhasználó tudatát befolyásolni képes, 
    ráadásul a legtöbb felhasználóban félelmet kelt, hogy a közösségi médiában viszontlátja a korábbi vásárlási szokásait vagy csevegési témáit.
</p>