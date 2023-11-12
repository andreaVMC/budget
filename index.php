<?php
    @require 'config.php';
    session_reset();
    $connessione = instauraConnessione();
    $va=00;
    if(isset($_POST['change_guadagni'])){
        $Id_record=$_POST['change_guadagni'];
        aggiornaRecordStato($Id_record,"guadagni",$connessione);
    }else if(isset($_POST['change_spese'])){
        $Id_record=$_POST['change_spese'];
        aggiornaRecordStato($Id_record,"spese",$connessione);
    }

    if(isset($_POST['aggiungi_guadagno'])){
        $etichetta=$_POST['etichetta'];
        $valore=$_POST['valore'];
        $data=$_POST['data'];
        $stato=$_POST['stato'];
        if(!inserisci_guadagno($connessione,$etichetta,$valore,$data,$stato)){
            //errore
        }
    }else if(isset($_POST['aggiungi_spesa'])){
        $etichetta=$_POST['etichetta'];
        $categoria_Id=$_POST['categoria'];
        $valore=$_POST['valore'];
        $data=$_POST['data'];
        $stato=$_POST['stato'];
        if(!inserisci_spesa($connessione,$etichetta,$categoria_Id,$valore,$data,$stato)){
            //errore
        }
    }else if(isset($_POST['aggiungi_categoria'])){
        $etichetta=$_POST['etichetta'];
        $grado=$_POST['grado'];
        if(!inserisci_categoria($connessione,$etichetta,$grado)){
            //errore
        }
    }



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget</title>
    <link rel="icon" href="IMG/favicon.png" type="image/x-icon">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap');
        body{
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-x: hidden;
            font-family: 'Roboto Condensed', sans-serif;
            scroll-behavior:smooth;
        }

        .navBar{
            /*border:1px solid red;*/
            width:35%;
            display:grid;
            grid-template-columns:repeat(3,1fr);
            margin: 2% 2%;
            padding: 1% 2%;
            border-radius:5px;
            background-color:gray; 
            position:static;
        }

        .navBar>.cntnr{
            display:flex;
            flex-direction:column;
            align-items:center;
            /*border:1px solid green;*/
        }

        .size{
            border:1px solid red;
        }

        .navBar>.cntnr>button{
            width:80%;
            border-radius: 5px;
            padding: 2%;
        }

        .resoconti{
            /*border:1px solid green;*/
            display:flex;
            flex-direction:column;
            align-items:center;
            width:80%;
            margin:2%;
        }

        .trDefault{
            background-color:#EAEAEA;
        }

        .resoconto_mensile,.resoconto_media{
            /*border:1px solid red;*/
            width:100%;
            display:flex;
            flex-direction:column;
            align-items:center;
            background-color:lightgray;
            padding:2% 2%;
            border-radius:5px;
            margin:2% 2%;
        }

        .mensile_tabelle,.media_tabelle{
            /*border:1px solid purple;*/
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: space-around;
        }

        .mediaTb,.mensileTb{
            width:40%;
        }

        .container_cronologia{
            /*border: 1px solid red;*/
            width: 80%;
            border-radius:5px;
            background-color: lightgray;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            align-items: center;
            padding: 2% 2%;
        }

        .titolo_cronologia{
            margin-bottom: 2%;
        }

        .container_tabelle{
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: space-around;
        }

        .container_guadagni{
            /*border: 1px solid red;*/
            width: 40%;
            display: flex;
            flex-direction:column;
        }

        .addRecord>button{
            margin:2% 2%;
        }

        .container_spese{
            /*border: 1px solid red;*/
            width: 40%;
            display: flex;
            flex-direction:column;
        }



        table{
            width: 100%;
            background-color: #222222;
            padding:2% 2%;
            border-radius:5px
        }

        td{
            text-align: center;
        }

        button{
            background-color: #222222;
            color:white;
            border-radius:5px;
            border: 1px solid transparent;
            cursor:pointer;
            transition-duration:0.2s;
        }

        button:hover{
            border-color:white;
        }

        button:active{
            border-color:#222222;
            color:#222222;
            background-color:white;
        }

        .Window_input {
            background-color: #F8F4F9;
            position: fixed;
            top:-200%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 25%;
            /*height: 75vh;*/
            /*display: none;*/
            border-radius:5px;
            box-shadow: 0px 0px 2000px black;
            transition-duration:0.5s;
            display:flex;
            flex-direction:column;
            align-items:center;
            padding: 2% 2%;
        }

        .Window_input>.top{
            display:grid;
            width:100%;
            grid-template-columns: 80% 20%;
            /*border:1px solid red;*/
            padding: 1% 1%;
        }

        .Window_input>.top>div{
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
        }

        .Window_input>.top>div>img{
            width: 25%;
            cursor:pointer;
        }

        .Window_input>.top>.titolo_window{
            font-size:150%;
            font-weight:700;
        }

        .Window_input>.input{
            /*border:1px solid red;*/
            padding:2% 2%;
            margin-top:5%;
            width: 80%;
            height: 40%;
            display:flex;
            flex-direction:column;
            align-items:center;
        }

        .Window_input>.input>.form_input{
            /*border:1px solid green;*/
            display:flex;
            flex-direction:column;
            height:100%;
        }

        .Window_input>.input>.form_input>input{
            margin-bottom:10%;
            font-size:125%;
            border:1px solid black;
            border-radius:5px;
            padding:2% 2%;
        }

        .Window_input>.input>.form_input>select{
            margin-bottom:10%;
            font-size:125%;
            border:1px solid black;
            border-radius:5px;
            padding:2% 2%;
        }

        /*controlla larghezza schermo, quando è troppo poca
        fami mettere tabelle guadagni e consumi in colonna,
        cambiando orientamento del flex di .container_tabelle*/
    </style>
</head>
<body onbeforeunload="saveScrollPosition()">
    <div class="titolo"><h1>Budget</h1></div>
    <div class="navBar" id="navBar"><!--bottoni in orizzontale per muoversi tra le varie parti-->
        <div class="cntnr">
            <button onclick="getElementById('resoconti').scrollIntoView({ behavior: 'smooth' });">resoconti</button>    
        </div>
        <div class="cntnr" onclick="getElementById('resoconti').scrollIntoView({ behavior: 'smooth' });">
            <button>cronologia</button>    
        </div>
        <div class="cntnr" onclick="getElementById('esporta').scrollIntoView({ behavior: 'smooth' });">
            <button>esporta</button>    
        </div>
    </div>

    <div class="resoconti" id="resoconti">
        <!--spesi e guadagnati questo mese-->
        <!--in cosa li hai spesi-->
        <!--paragone guadagni e spesi questo mese con la media-->
        <!--paragone spese per categorie mensile con la media-->

        <div class="resoconto_mensile">
            <div class="titolo"><h1>Mensile</h1></div>
            <div class="mensile_tabelle">
                <div class="mensileTb">
                    <table>
                        <tr style="background-color:lightgray;">
                            <th>guadagnato</th>
                            <th>spese</th>
                            <th>diff.</th>
                        </tr>
                        <!--prima dati poi seconda riga percentuali-->

                        <tr class="trDefault">
                            <td><?php echo getGuadagnatoMonth($connessione)."€"; ?></td>
                            <td><?php echo getSpesoMonth($connessione)."€"; ?></td>
                            <?php
                                $color="";
                                if(getDiffMonth($connessione)<0){
                                    $color="#F71735"; // negativo
                                }else{
                                    $color="#93FF96";
                                }
                            ?>
                            <td style="background-color:<?php echo $color ?>;">
                                <?php echo getDiffMonth($connessione)."€"; ?>
                            </td>
                        </tr>

                        <tr class="trDefault">
                            <td><?php echo getGuadagnatoMonthPerc($connessione)."%" ?></td>
                            <td><?php echo getSpesoMonthPerc($connessione)."%" ?></td>
                            <td style="background-color:<?php echo $color ?>;">
                                <?php echo getDifMonthPerc($connessione)."%"; ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="mensileTb">
                    <table>
                        <tr style="background-color:lightgray;">
                            <th>non neces.</th>
                            <th>neces.</th>
                            <th>invest.</th>
                        </tr>

                        <tr class="trDefault">
                           <td><?php echo getTotGradoMonth($connessione,0)."€"; /*non necessaria*/?></td>
                           <td><?php echo getTotGradoMonth($connessione,1)."€"; /*necessaria*/?></td>
                           <td><?php echo getTotGradoMonth($connessione,2)."€"; /*investimento*/?></td> 
                        </tr>

                        <tr class="trDefault">
                           <td><?php echo getTotGradoMonthPerc($connessione,0)."%" ?></td>
                           <td><?php echo getTotGradoMonthPerc($connessione,1)."%" ?></td>
                           <td><?php echo getTotGradoMonthPerc($connessione,2)."%" ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="resoconto_media">
            <div class="titolo"><h1>Paragone</h1></div>
            <div class="media_tabelle">
                <div class="mediaTb">
                    <table>
                        <tr style="background-color:lightgray;">
                            <th></th>
                            <th>guadagno</th>
                            <th>spesa</th>
                            <th>diff.</th>
                        </tr>
                        <tr class="trDefault">
                            <th style="background-color:lightgray;">mese</th>
                            <td><?php echo getGuadagnatoMonthPerc($connessione)."%" ?></td>
                            <td><?php echo getSpesoMonthPerc($connessione)."%" ?></td>
                            <td style="background-color:<?php echo $color ?>;">
                                <?php echo getDifMonthPerc($connessione)."%"; ?>
                            </td>
                        </tr>
                        <?php
                            $color="";
                            if(getMediaDifAllMonthPerc($connessione)<0){
                                $color="#F71735"; // negativo
                            }else{
                                $color="#93FF96";
                            }
                        ?>
                        <tr class="trDefault">
                            <th style="background-color:lightgray;">media</th>
                            <td><?php echo getMediaGuadagnoAllMonthPerc($connessione)."%" ?></td>
                            <td><?php echo getMediaSpesaAllMonthPerc($connessione)."%" ?></td>
                            <td style="background-color:<?php echo $color; ?>"><?php echo getMediaDifAllMonthPerc($connessione)."%" ?></td>
                        </tr>


                        <tr class="trDefault">
                            <td style="background-color:lightgray;"></td>
                            <td><?php echo getMediaGuadagnoAllMonth($connessione)."€" ?></td>
                            <td><?php echo getMediaSpesaAllMonth($connessione)."€" ?></td>
                            <td style="background-color:<?php echo $color; ?>"><?php echo getMediaDifAllMonth($connessione)."€" ?></td>
                        </tr>
                    </table>
                </div>
                <div class="mediaTb">
                    <table>
                        <tr style="background-color:lightgray;">
                            <th></th>
                            <th>non neces.</th>
                            <th>neces.</th>
                            <th>invest.</th>
                        </tr>
                        <tr class="trDefault">
                            <th style="background-color:lightgray;">mese</th>
                            <td><?php echo getTotGradoMonthPerc($connessione,0)."%" ?></td>
                            <td><?php echo getTotGradoMonthPerc($connessione,1)."%" ?></td>
                            <td><?php echo getTotGradoMonthPerc($connessione,2)."%" ?></td>
                        </tr>
                        <tr class="trDefault">
                            <th style="background-color:lightgray;">media</th>
                            <td><?php echo getTotGradoAllPerc($connessione,0)."%"; ?></td>
                            <td><?php echo getTotGradoAllPerc($connessione,1)."%"; ?></td>
                            <td><?php echo getTotGradoAllPerc($connessione,2)."%"; ?></td>
                        </tr>
                        <tr class="trDefault">
                            <th style="background-color:lightgray;"></th>
                            <td><?php echo getTotGradoAll($connessione,0)."€"; ?></td>
                            <td><?php echo getTotGradoAll($connessione,1)."€"; ?></td>
                            <td><?php echo getTotGradoAll($connessione,2)."€"; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="container_cronologia" id="cronologia">
        <div class="titolo_cronologia">
            <h2>Cronologia</h2>
        </div>
        <div class="container_tabelle">
            <div class="container_guadagni">
                <div class="addRecord">
                    <button onclick="windowAggiungiGuadagno()">aggiugni</button>
                </div>
                    <table>
                        <tr style="background-color: lightgray">
                            <th>etichetta</th>
                            <th>valore</th>
                            <th>data</th>
                            <th>stato</th>
                        </tr>
                    <?php    
                        $stmt = $connessione->prepare("SELECT * FROM `guadagni` ORDER BY data DESC");
                        $stmt->execute([]);
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $color="";
                        foreach ($results as $result){
                            if($result['stato']==0){
                                $color="#93FF96"; //confermato
                            }else if($result['stato']==1){
                                $color="#FF8811"; //da confermare
                            }else if($result['stato']==2){
                                $color="#BADEFC"; //previsto
                            }else {
                                $color="#F71735"; //errore
                            }
                            
                            echo "<tr style='background-color:$color'>";

                            echo "<td>".$result['etichetta']."</td>";
                            echo "<td>".$result['valore']."€</td>";
                            echo "<td>".$result['data']."</td>";
                            echo "<td>
                                <form style='display:none;' action='" . $_SERVER['PHP_SELF'] . "' method='POST'>
                                    <input type='submit' id='change_guadagni" . $result['Id'] . "' name='change_guadagni' value='" . $result['Id'] . "'>
                                </form>
                                <button onclick=\"document.getElementById('change_guadagni" . $result['Id'] . "').click()\">cambia</button>
                            </td>";
                            echo "</tr>";
                        }
                    ?>
                    </table>
            </div>
            <div class="container_spese">
            <div class="addRecord">
                    <button onclick="windowAggiungiSpesa()">aggiugni</button>
                    <button onclick="windowAggiungiCategoria()">categoria</button>
                </div>
                <table>
                    <tr style="background-color: lightgray">
                        <th>etichetta</th>
                        <th>categoria</th>
                        <th>valore</th>
                        <th>data</th>
                        <th>stato</th>
                    </tr>
                    <?php    
                        $stmt = $connessione->prepare("SELECT * FROM `spese` ORDER BY data DESC");
                        $stmt->execute([]);
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $color="";
                        foreach ($results as $result){
                            if($result['stato']==0){
                                $color="#93FF96"; //confermato
                            }else if($result['stato']==1){
                                $color="#FF8811"; //da confermare
                            }else if($result['stato']==2){
                                $color="#BADEFC"; //previsto
                            }else {
                                $color="#F71735"; //errore
                            }


                            echo "<tr style='background-color:$color'>";
                            echo "<td>".$result['etichetta']."</td>";


                            if(getGradoCategoria($result['Id_categoria'],$connessione)==0){
                                $color="#EE6352"; //non necessaria
                            }else if(getGradoCategoria($result['Id_categoria'],$connessione)==1){
                                $color="#59CD90"; //necessaria
                            }else if(getGradoCategoria($result['Id_categoria'],$connessione)==2){
                                $color="#3FA7D6"; //investimento
                            }else {
                                $color="#F71735"; //errore
                            }
                            echo "<td style='background-color:".$color."'>".
                                getNameCategoria($result['Id_categoria'],$connessione).
                            "</td>";

                            echo "<td>".$result['valore']."€</td>";
                            echo "<td>".$result['data']."</td>";
                            echo "<td>
                                <form style='display:none;' action='" . $_SERVER['PHP_SELF'] . "' method='POST'>
                                    <input type='submit' id='change_spese" . $result['Id'] . "' name='change_spese' value='" . $result['Id'] . "'>
                                </form>
                                <button onclick=\"document.getElementById('change_spese" . $result['Id'] . "').click()\">cambia</button>
                            </td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <div class="esportazione" id="esporta">
        <!--scarica file pdf e csv con tutti i dati di transazione-->
    </div>

    <div class="Window_input aggiungi_guadagni">
        <div class="top">
            <div class="titolo_window">Aggiugni Guadagni</div>
            <div onclick=" windowAggiungiGuadagno()">
                <img src="IMG/x-button.png">
            </div>
        </div>
        <div class="input">
            <form class="form_input" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                <input placeholder="etichetta" name="etichetta" type="text">
                <input placeholder="valore" type="number" name="valore">
                <input type="date" name="data" value="<?php echo date('Y-m-d'); ?>"> 
                <select name="stato">
                        <option value="0">ricevuto</option>
                        <option value="1">attesa</option>
                        <option value="2">previsto</option>
                </select>
                <input id="aggiungi_guadagno" placeholder="input" type="submit" style="display:none;" name="aggiungi_guadagno">
            </form>
            <button onclick="document.getElementById('aggiungi_guadagno').click()">aggiungi</button>
        </div>
    </div>

    <div class="Window_input aggiungi_spese">
        <div class="top">
            <div class="titolo_window">Aggiugni Spese</div>
            <div onclick=" windowAggiungiSpesa()">
                <img src="IMG/x-button.png">
            </div>
        </div>
        <div class="input">
            <form class="form_input" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                <input placeholder="etichetta" name="etichetta" type="text">
                <select name="categoria">
                    <?php
                        $stmt = $connessione->prepare("SELECT * FROM `categorie`");
                        $stmt->execute([]);
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($results as $result){
                            echo "<option value=\"".$result['Id']."\">".$result['nome']."</option>";
                        }
                    ?>
                </select>
                <input placeholder="valore" type="number" name="valore">
                <input placeholder="data" type="date" name="data" value="<?php echo date('Y-m-d'); ?>">
                <select name="stato">
                        <option value="0">ricevuto</option>
                        <option value="1">attesa</option>
                        <option value="2">previsto</option>
                </select>
                <input id="aggiungi_spesa" placeholder="input" type="submit" style="display:none;" name="aggiungi_spesa">
            </form>
            <button onclick="document.getElementById('aggiungi_spesa').click()">aggiungi</button>
        </div>
    </div>

    <div class="Window_input aggiungi_categorie">
        <div class="top">
            <div class="titolo_window">Aggiugni Categorie</div>
            <div onclick=" windowAggiungiCategoria()">
                <img src="IMG/x-button.png">
            </div>
        </div>
        <div class="input">
            <form class="form_input" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                <input placeholder="etichetta" type="text" name="etichetta">
                <select name="grado">
                    <option value="0">non necessario</option>
                    <option value="1">necessario</option>
                    <option value="2">investimento</option>
                </select>
                <input id="aggiungi_categoria" placeholder="input" type="submit" style="display:none;" name="aggiungi_categoria">
            </form>
            <button onclick="document.getElementById('aggiungi_categoria').click()">aggiungi</button>
        </div>
    </div>

    <script>
        window.addEventListener('scroll', function() {
            var navbar = document.getElementById('navBar');
            console.log(window.scrollY);
            // Verifica se la posizione verticale dello scroll è maggiore di un certo valore (ad esempio, 100 pixel)
            if (window.scrollY > 1) {
                navbar.style.position = 'fixed';
            } else {
                navbar.style.position = 'static';
            }
        });


        // Funzione per salvare la posizione corrente dello scroll
        function saveScrollPosition() {
            sessionStorage.setItem('scrollPosition', window.scrollY);
        }

        // Funzione per ripristinare la posizione dello scroll
        function restoreScrollPosition() {
            const scrollPosition = sessionStorage.getItem('scrollPosition');
            if (scrollPosition) {
                window.scrollTo(0, parseInt(scrollPosition));
            }
        }

        // Registra l'evento "beforeunload" per salvare la posizione prima di ricaricare la pagina
        window.addEventListener('beforeunload', saveScrollPosition);

        // Ripristina la posizione dello scroll dopo il caricamento della pagina
        window.addEventListener('load', restoreScrollPosition);

        function windowAggiungiGuadagno() {
    var guadagniElements = document.getElementsByClassName('aggiungi_guadagni');
    var speseElements = document.getElementsByClassName('aggiungi_spese');
    var categorieElements = document.getElementsByClassName('aggiungi_categorie');

    for (var i = 0; i < guadagniElements.length; i++) {
        if (guadagniElements[i].style.top === '50%') {
            guadagniElements[i].style.top = '-200%';
        } else {
            guadagniElements[i].style.top = '50%';
        }
    }

    // Assicurati che le altre due classi siano impostate su -200%
    for (var j = 0; j < speseElements.length; j++) {
        speseElements[j].style.top = '-200%';
    }

    for (var k = 0; k < categorieElements.length; k++) {
        categorieElements[k].style.top = '-200%';
    }
}

function windowAggiungiSpesa() {
    var guadagniElements = document.getElementsByClassName('aggiungi_guadagni');
    var speseElements = document.getElementsByClassName('aggiungi_spese');
    var categorieElements = document.getElementsByClassName('aggiungi_categorie');

    for (var i = 0; i < speseElements.length; i++) {
        if (speseElements[i].style.top === '50%') {
            speseElements[i].style.top = '-200%';
        } else {
            speseElements[i].style.top = '50%';
        }
    }

    // Assicurati che le altre due classi siano impostate su -200%
    for (var j = 0; j < guadagniElements.length; j++) {
        guadagniElements[j].style.top = '-200%';
    }

    for (var k = 0; k < categorieElements.length; k++) {
        categorieElements[k].style.top = '-200%';
    }
}

function windowAggiungiCategoria() {
    var guadagniElements = document.getElementsByClassName('aggiungi_guadagni');
    var speseElements = document.getElementsByClassName('aggiungi_spese');
    var categorieElements = document.getElementsByClassName('aggiungi_categorie');

    for (var i = 0; i < categorieElements.length; i++) {
        if (categorieElements[i].style.top === '50%') {
            categorieElements[i].style.top = '-200%';
        } else {
            categorieElements[i].style.top = '50%';
        }
    }

    // Assicurati che le altre due classi siano impostate su -200%
    for (var j = 0; j < guadagniElements.length; j++) {
        guadagniElements[j].style.top = '-200%';
    }

    for (var k = 0; k < speseElements.length; k++) {
        speseElements[k].style.top = '-200%';
    }
}



    </script>
</body>
</html>

<?php
    function instauraConnessione(){
        try {
            // Stabilisco la connessione al database
            $connessione = new PDO(
                "mysql:host=".$GLOBALS['dbhost'].";dbname=".$GLOBALS['dbname'],
                $GLOBALS['dbuser'],
                $GLOBALS['dbpassword']
            );
            $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connessione;
        }catch(PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }



    function aggiornaRecordStato($Id, $table, $connessione){
        $stmt = $connessione->prepare("SELECT stato FROM $table WHERE Id=?");
        $stmt->execute([$Id]);
        $result = $stmt->fetchColumn(); // Ottieni il valore di "stato"
        
        $result++;
        if($result==3){
            $result=0;
        }


        $stmt = $connessione->prepare("UPDATE $table SET stato = $result WHERE Id = ?");
        $stmt->execute([$Id]);
    }
    
    function getNameCategoria($Id,$connessione){
        $stmt = $connessione->prepare("SELECT nome FROM `categorie` WHERE Id=?");
        $stmt->execute([$Id]);
        $result = $stmt->fetchColumn(); // Ottieni il valore di "stato"
        return $result;
    }

    function getGradoCategoria($Id,$connessione){
        $stmt = $connessione->prepare("SELECT grado FROM `categorie` WHERE Id=?");
        $stmt->execute([$Id]);
        $result = $stmt->fetchColumn(); // Ottieni il valore di "stato"
        return $result;
    }

    function getGuadagnatoMonth($connessione){
        $month = date('m'); // Ottiene il mese corrente nel formato "MM"
        $stmt = $connessione->prepare("SELECT SUM(valore) AS totale_guadagni FROM `guadagni` WHERE MONTH(data) = :month");
        $stmt->bindParam(':month', $month, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result['totale_guadagni']==null){
            return number_format(0, 2);
        }
        return $result['totale_guadagni'];
    }

    function getSpesoMonth($connessione){
        $month = date('m'); // Ottiene il mese corrente nel formato "MM"
        $stmt = $connessione->prepare("SELECT SUM(valore) AS totale_spese FROM `spese` WHERE MONTH(data) = :month");
        $stmt->bindParam(':month', $month, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result['totale_spese']==null){
            return number_format(0, 2);
        }
        return $result['totale_spese'];
    }
    
    function getDiffMonth($connessione){
        $result=getGuadagnatoMonth($connessione)-getSpesoMonth($connessione);
        return number_format($result, 2);
    }
    
    function getGuadagnatoMonthPerc($connessione){
        $tot=getGuadagnatoMonth($connessione)+getSpesoMonth($connessione);
        $percentuale=0;
        if($tot!=0)
            $percentuale= (getGuadagnatoMonth($connessione)*100)/$tot;
        $percentuale = number_format($percentuale, 2);
        return $percentuale;
    }

    function getSpesoMonthPerc($connessione){
        $tot=getGuadagnatoMonth($connessione)+getSpesoMonth($connessione);
        $percentuale=0;
        if($tot!=0)
            $percentuale= (getSpesoMonth($connessione)*100)/$tot;
        $percentuale = number_format($percentuale, 2);
        return $percentuale;
    }

    function getDifMonthPerc($connessione){
        $result=getGuadagnatoMonthPerc($connessione)-getSpesoMonthPerc($connessione);   
        return number_format($result, 2);
    }

    function getTotGradoMonth($connessione,$grado){
        $month = date('m'); // Ottiene il mese corrente nel formato "MM"
        $stmt = $connessione->prepare("SELECT * FROM `spese` WHERE MONTH(data) = :month");
        $stmt->bindParam(':month', $month, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $somma=0;

        foreach($result as $results){
            if(getGradoByIdCategoria($connessione,$results['Id_categoria'])==$grado){
                $somma+=$results['valore'];
            }
        }

        return number_format($somma, 2);
    }

    function getGradoByIdCategoria($connessione, $Id){
        $stmt = $connessione->prepare("SELECT * FROM `categorie` WHERE Id = :id");
        $stmt->bindParam(':id', $Id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Assicurati di restituire il valore di "grado"
        return $result['grado'];
    }
    
    function getTotGradoMonthPerc($connessione,$grado){
        $result=0;
        if(getSpesoMonth($connessione)!=0){
            $result = (getTotGradoMonth($connessione,$grado)*100)/getSpesoMonth($connessione);
            $result = number_format($result, 2);
        }else{
            $result = number_format(0, 2);
        }
        return $result;
    }

    function getMediaGuadagnoAllMonth($connessione) {
        // Ottieni la data corrente
        $dataCorrente = new DateTime();
        
        // Estrai il mese e l'anno corrente
        $meseCorrente = $dataCorrente->format('m');
        $annoCorrente = $dataCorrente->format('Y');
    
        // Query per sommare i valori, escludendo i record del mese corrente
        $query = "SELECT SUM(valore) AS totale_somma FROM guadagni WHERE YEAR(data) != :annoCorrente OR MONTH(data) != :meseCorrente";
        
        // Esegui la query
        $stmt = $connessione->prepare($query);
        $stmt->bindParam(':annoCorrente', $annoCorrente, PDO::PARAM_INT);
        $stmt->bindParam(':meseCorrente', $meseCorrente, PDO::PARAM_INT);
        $stmt->execute();
        
        // Estrai il risultato
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Restituisci la somma escludendo il mese corrente
        return number_format($result['totale_somma']/totMonthGuadagno($connessione),2);

    }

    function getMediaSpesaAllMonth($connessione) {
        // Ottieni la data corrente
        $dataCorrente = new DateTime();
        
        // Estrai il mese e l'anno corrente
        $meseCorrente = $dataCorrente->format('m');
        $annoCorrente = $dataCorrente->format('Y');
    
        // Query per sommare i valori, escludendo i record del mese corrente
        $query = "SELECT SUM(valore) AS totale_somma FROM spese WHERE YEAR(data) != :annoCorrente OR MONTH(data) != :meseCorrente";
        
        // Esegui la query
        $stmt = $connessione->prepare($query);
        $stmt->bindParam(':annoCorrente', $annoCorrente, PDO::PARAM_INT);
        $stmt->bindParam(':meseCorrente', $meseCorrente, PDO::PARAM_INT);
        $stmt->execute();
        
        // Estrai il risultato
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Restituisci la somma escludendo il mese corrente
        return number_format($result['totale_somma']/totMonthSpesa($connessione),2);
    }
    

    function totMonthSpesa($connessione){
        $stmt = $connessione->prepare("SELECT MIN(data) AS min_data FROM spese");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Estrai la data minima
        $minData = new DateTime($result['min_data']);
        
        // Ottieni la data del mese scorso
        $meseScorso = new DateTime('last month');
        
        // Calcola la differenza in mesi
        $differenza = $minData->diff($meseScorso);
        
        // Restituisci la differenza in mesi come un numero intero
        return $differenza->y * 12 + $differenza->m +2;
    }

    function totMonthGuadagno($connessione){
        $stmt = $connessione->prepare("SELECT MIN(data) AS min_data FROM guadagni");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Estrai la data minima
        $minData = new DateTime($result['min_data']);
        
        // Ottieni la data del mese scorso
        $meseScorso = new DateTime('last month');
        
        // Calcola la differenza in mesi
        $differenza = $minData->diff($meseScorso);
        
        // Restituisci la differenza in mesi come un numero intero
        return $differenza->y * 12 + $differenza->m +2;
    }
    
    
    function getMediaDifAllMonth($connessione){
        $result = getMediaGuadagnoAllMonth($connessione)-getMediaSpesaAllMonth($connessione);
        $result = number_format($result, 2);
        return $result;
    }

    function getMediaGuadagnoAllMonthPerc($connessione){
        $result=0;
        $tot= getMediaGuadagnoAllMonth($connessione)+getMediaSpesaAllMonth($connessione);
        if($tot!=0)
            $result = (getMediaGuadagnoAllMonth($connessione)*100)/$tot;
        $result = number_format($result, 2);
        return $result;
    }

    function getMediaSpesaAllMonthPerc($connessione){
        $result=0;
        $tot= getMediaGuadagnoAllMonth($connessione)+getMediaSpesaAllMonth($connessione);
        if($tot!=0)
            $result = (getMediaSpesaAllMonth($connessione)*100)/$tot;
        $result = number_format($result, 2);
        return $result;
    }
    
    function getMediaDifAllMonthPerc($connessione){
        return getMediaGuadagnoAllMonthPerc($connessione)-getMediaSpesaAllMonthPerc($connessione);
    }



    /*
        trovare mese prima spesa assoluta dell' anno, variabile totale mesi (prima spesa anno - mese scorso)
        somma valori per grado e per mese di tutti i mesi dell' anno escluso il corrente
        dividere somma medie per mese con totale mesi da prima registrazione
        ritornare il valore
    */

    function getTotGradoAll($connessione, $grado) {
        $dataCorrente = new DateTime();
        $meseCorrente = $dataCorrente->format('m');
        $annoCorrente = $dataCorrente->format('Y');
    
        $stmt = $connessione->prepare("SELECT * FROM `spese` WHERE (YEAR(data) != :annoCorrente) OR (YEAR(data) = :annoCorrente AND MONTH(data) != :meseCorrente)");
        $stmt->bindParam(':annoCorrente', $annoCorrente, PDO::PARAM_STR);
        $stmt->bindParam(':meseCorrente', $meseCorrente, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $somma = 0;
    
        foreach ($result as $results) {
            if (getGradoByIdCategoria($connessione, $results['Id_categoria']) == $grado) {
                $somma += $results['valore'];
            }
        }
    
        return number_format($somma/totMonthSpesa($connessione), 2);
    }

    function getTotGradoAllPerc($connessione,$grado){
        $tot=0;
        for($i=0;$i<3;$i++)
            $tot+=getTotGradoAll($connessione,$i);

        $perc=0;
        if($tot!=0){
            $perc=(getTotGradoAll($connessione,$grado)*100)/$tot;
        }
        return number_format($perc,2);

    }

    function inserisci_guadagno($connessione,$etichetta,$valore,$data,$stato){
        $stmt = $connessione->prepare("INSERT INTO `guadagni`(`etichetta`, `valore`, `data`, `stato`) VALUES (?,?,?,?)");
        $stmt->execute([$etichetta,$valore,$data,$stato]);
        return true;
    }

    function inserisci_spesa($connessione,$etichetta,$categoria,$valore,$data,$stato){
        $stmt = $connessione->prepare("INSERT INTO `spese`(`etichetta`, `Id_categoria`, `valore`, `data`, `stato`) VALUES (?,?,?,?,?)");
        $stmt->execute([$etichetta,$categoria,$valore,$data,$stato]);
        return true;
    }

    function inserisci_categoria($connessione,$etichetta,$grado){
        $stmt = $connessione->prepare("INSERT INTO `categorie`(`nome`, `grado`) VALUES (?,?)");
        $stmt->execute([$etichetta,$grado]);
        return true;
    }
?>