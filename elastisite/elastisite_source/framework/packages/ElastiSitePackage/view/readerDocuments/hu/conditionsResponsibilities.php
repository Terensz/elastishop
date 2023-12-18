<div class="widgetWrapper">
<b>Felelősségi körök</b>
<br><br>

<b>A tartalom felelőssége</b>
<br><br>

A tartalom létrehozásáért a Megrendelő felel.






<br><br>

<b>A szoftver helyes működésének felelőssége</b>
<br><br>
A szoftver akkor működik helyesen, ha a működése megfelel a Felhasználói kézikönyvben foglaltaknak.<br>
Amennyiben nem ez történik, úgy a helyes működést annak kell helyreállítania, akinek a mulasztásából a hibás működés bekövetkezett.

<b>Hibák, és a hozzájuk tartozó felelősségek</b>

<br><br>

<b>A hibakóddal rendelkező hibák</b>

<br><br>

Az ElastiSite alapú webes alkalmazások nagyon sok esetben meg tudják állapítani a pontos hibát. Ilyenkor egy tájékoztatást jelenítenek meg a hibáról, és kiírnak egy 4-jegyű hibakódot.

<br>

Ez a négyjegyű hibakód az ElastiSite keretrendszer saját hibakódja, a sorszámozása és szövegezése a Webfejlesztő által történt.

<br><br>

Azokban az esetekben, amikor a hibakód 14-gyel kezdődik, a hiba felelőse az Üzemeltető. A hibát az okozza, hogy Az üzemeltető nem teljesítette a Beüzemelési Kézikönyvben foglaltakat, vagy a későbbiek során olyan beállítást végzett a Web- vagy adatbázis-kiszolgálón, ami miatt az már nem teljesítette az Üzemeltetési Kézikönyvben foglaltakat. (Pl. egy file-nak vagy egy mappámak megváltozott a jogosultsága, és a webes alkalmazás már nem képes azt módosítani, vagyis hibára fog futni a program)

<br><br>

Azokban az esetekben, amikor a hibakód 15-tel vagy 16-tal kezdődik, ott programhiba történt, és a Webfejlesztő hatáskörébe tartozik a hiba elhárítása.

<br><br>

<b>A tanusítvány-hibák</b>

<br><br>

A hibaüzenet nagyon erősen árulkodik a típusáról. "A biztonságos kapcsolat sikertelen", vagy "Ez a kapcsolat nem megbízható" szokott lenni a hibaüzenet.<br>
Ez alapesetben nem a Webfejlesztő felelőssége. Kivéve az az eset, amennyiben a Megrendelő <b>Beüzemelési tehermentesítés</b> szolgáltatást rendelt, és a szoftver átadása óta nem telt el 30 nap: ilyenkor a Webfejlesztőnek kell intéznie a hiba elhárítását. Ugyanis ezzel a szolgáltatással a Webfejlesztő magára vállalt egy felelősségi kört, ami alapesetben nem az övé lenne. 

<br><br>

Amennyiben a Megrendelő nem kért <b>Beüzemelési tehermentesítés</b> szolgáltatást, vagy kért, de már eltelt 30 nap a beüzemelés óta (nyilván 1 év telt el, mert 1 évre szól a tanusítvány), akkor ez a hiba a Webfejlesztő hatás- és felelősségi körén kívül van, és őt nem kell ezzel kapcsolatban megkeresni. Érdemes ilyenkor a Tanusítvány-kiállítóval felvenni a kapcsolatot.

<br><br>

<b>Connection timeout / Időtúllépés</b>

<br><br>

Ilyen esetben meg kell nézni, hogy egy másik internetkapcsolattal működik-e a webes alkalmazás. (Az a telefon, ami ugyanarról a routerről kapja az internetet, amihez vezetékesen kapcsolódik a számítógép, amin jelentkezett a hiba: nem számít másik internetkapcsolatnak). Ha a másik készülékről elérhető a webhely, és működik a webes alkalmazás, akkor a webes alkalmazás működik, és ezzel az esettel sem a Webfejlesztőnek, sem az Üzemeltetőnek nincs tennivalója.

<br><br>

Ha másik internetkapcsolatról sem működik, akkor az Üzemeltető hatáskörébe tartozik a hiba.

<br><br>

<b>500 Internal Server Error</b>

<br><br>

Ez a "legrosszabb" hibaüzenet, ilyenkor 50%, hogy a Webfejlesztő, és 50%, hogy az Üzemeltető hatáskörébe tartozik a hiba. Amennyiben a webes alkalmazás még a jótállási időn belül van, úgy a Webfejlesztővel érdemes felvenni a kapcsolatot. Ha már kívül, akkor pedig az Üzemeltetővel.

<!-- A webfejlesztő <br> -->
</div>