<?php

require "vendor/autoload.php";
require "vendor/monkeylearn/monkeylearn-php/autoload.php";

$ml = new MonkeyLearn\Client('831e349064703038eb3d96a710ca316a5395a1c9');
use Abraham\TwitterOAuth\TwitterOAuth;
require_once('TwitterAPIExchange.php');
/** Set access tokens here - see: https://dev.twitter.com/apps/ **/

$host="localhost";
$user="root";
$psw="1996@walid";
$db="tweet";

$cn = mysqli_connect($host,$user,$psw,$db);
$cn->set_charset("utf8");


$nat = 0;
$pos = 0;
$neg = 0;
$count=0;



if(!$cn){ die(mysqli_connect_error());}

$affichage = ""; 
$comments="";
    
if(isset($_POST['idT'])){
  
  $id = $_POST['idT'];

  $settings = array(
      'oauth_access_token' => "256576529-KbUKkw9Ayc8Ts6Jqqrh9FsamKuvK4emsZcDGGr9w",
      'oauth_access_token_secret' => "kDDpsoQXHdVdxuBMqIFqHJQNq31uVNngI6PTEwemMt4pJ",
      'consumer_key' => "mf10FWkrB4xRjo0doLGznJlrg",
      'consumer_secret' => "jRextB3mhSRvUTX4PtPhbGvW6T0d0ZDyBQHMeSCr8Hul54jLYH"
  );

  $i=0;

  $url = "https://api.twitter.com/1.1/statuses/retweets/$id.json";

  $requestMethod = "GET";

  $twitter = new TwitterAPIExchange($settings);
  $getfield = "";
  $string = json_decode($twitter ->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest(),true);

  
  
  foreach($string as $tweets) {

    $time = $tweets['created_at'];
    $id = $tweets['id'];
    $text = $tweets['user'];
    $comment = $text['description'];

    if($comment != null && $comment != ""){

      $data = ["$comment"];
      $model_id = 'cl_pi3C7JiL';
      $res = $ml->classifiers->classify($model_id, $data);

      /*
      $sql = "insert into comments values ($id,'$comment')";
      mysqli_query($cn,$sql);*/

      $sun = "";
      $pr = "";


      if($res->result[0]["classifications"][0]["tag_name"] != null && $res->result[0]["classifications"][0]["confidence"] != null)
      {
          $sun = $res->result[0]["classifications"][0]["tag_name"];
          $pr = ($res->result[0]["classifications"][0]["confidence"]*100);
      }


      $pr = number_format($pr, 2, '.', '');
      $comments .="<tr>
        <th scope='row'>$id</th>
        <td>$comment</td>
        <td>$sun</td>
        <td>$pr%</td>
      </tr>";

      if($sun == "Positive")$pos++;
      if($sun == "Neutral")$nat++;
      if($sun == "Negative")$neg++;

      $count++;
      
    }
    // Request Monkey Api 

    

  }

  $affichage="<div class='alert'>Insertions  Success</div>";
}

$porNat = 0;
$porPos = 0;
$porNeg = 0;



if($count != 0){

  $porPos = ($pos*100)/$count;
  $porNat = ($nat*100)/$count;
  $porNeg = ($neg*100)/$count;

}
  

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sentiment Analysis</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/icofont/icofont.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: ComingSoon - v2.2.0
  * Template URL: https://bootstrapmade.com/comingsoon-free-html-bootstrap-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="d-flex align-items-center">
    <div class="container d-flex flex-column">

      <h1 class="text-center">Sentiment Analysis</h1>

      <div class="title">Définition :</div>

      <p class="txt">
        L'analyse des sentiments (ou extraction d'opinions) est une technique de traitement du langage naturel utilisée pour déterminer si les données sont positives, négatives ou neutres. L'analyse des sentiments est souvent effectuée sur des données textuelles pour aider les entreprises à surveiller le sentiment de marque et de produit dans les commentaires des clients et à comprendre les besoins des clients.
      </p>

      <hr>

      <div class="title">Outils Api :</div>

      <div id="container" style="white-space:nowrap">
          <div id="image" style="display:inline;">
              <img src="assets/img/img1.png" width="150px" height="100px" />
          </div>

          <div id="texts" style="display:inline; white-space:nowrap;"> 
            AmazoneML API :  Amazone Machine learning API 
          </div>
      </div>
      <br>
      <div id="container" style="white-space:nowrap">
          <div id="image" style="display:inline;">
              <img src="assets/img/img2.png" width="150px" height="100px" />
          </div>

          <div id="texts" style="display:inline; white-space:nowrap;"> 
            Ayelien Text Analysis 
          </div>
      </div>
      <br>
      <div id="container" style="white-space:nowrap">
          <div id="image" style="display:inline;">
              <img src="assets/img/img3.png" width="150px" height="100px" />
          </div>

          <div id="texts" style="display:inline; white-space:nowrap;"> 
          Microsoft Content Moderator
          </div>
      </div>
      <br><div id="container" style="white-space:nowrap">
          <div id="image" style="display:inline;">
              <img src="assets/img/img4.png" width="150px" height="100px" />
          </div>

          <div id="texts" style="display:inline; white-space:nowrap;"> 
            IBM Watson API 
          </div>
      </div>
      <br>
      <div id="container" style="white-space:nowrap">
          <div id="image" style="display:inline;">
              <img src="assets/img/img5.png" width="150px" height="100px" />
          </div>

          <div id="texts" style="display:inline; white-space:nowrap;"> 
            Monkey Learn
          </div>
      </div>
      <br>
      <div id="container" style="white-space:nowrap">
          <div id="image" style="display:inline;">
              <img src="assets/img/img6.png" width="150px" height="100px" />
          </div>

          <div id="texts" style="display:inline; white-space:nowrap;"> 
           Kairos API 
          </div>
      </div>

      <hr>

      <div class="title">Outils Utilisées : </div>

      <p class="txt">
        Twitter Api  :<br>
        
          - Récupération des tweets , Retweets , Comments , Likes , ……<br>

        MonkeyLearn : <br>

          - Cette API automatise la classification de texte avec des modèles d'apprentissage automatique.
      </p>


      <hr>



      <div class="title">Liste des terminaux</div>

      <p class="txt">
        - classify <br>
        - classifyMulti <br>
        - createClassifier <br>
        - createClassifierCategory <br>
        - deleteClassifier<br>
        - deleteClassifierCategory ...<br>
      </p>

      <hr>
      

      <div class="title">Comment acquérir une clé API MonkeyLearn</div>
      <p class="txt">
            - Connectez-vous à MonkeyLearn.<br>
            - Accédez à la section Clés API.<br>
            - Générez votre jeton API.<br>
            - Tarification de l'API MonkeyLearn<br>
            - Plan gratuit: 300 requêtes par mois<br>
            - Plan d'équipe: 30000 questions à 299 $ par mois<br>
            - Plan d'affaires: 300 000 questions à 999 $ par mois<br>
            - Plan d'entreprise: nombre de requêtes selon la demande<br>
      </p>


      <hr>


      <div class="title">Langage de programmation : PHP , JS</div>

      <div class="imj20"></div>
      

      




      <br><br><br><br><br><br><br><br>





















      <h2>Il s'agit d'un classificateur d'analyse des sentiments générique pour les textes en anglais. Cela fonctionne très bien dans tout type de texte</h2>
      
      <div class="subscribe d-flex flex-column align-items-center">
        <h4>Tapez L'Id Du Tweet!</h4>


        <form action="#" method="post" role="" class="">
          <div class="subscribe-form">
            <input type="text" name="idT">
            <input type="submit" value="Start">
          </div>
          <div class="mt-2">
            <div class="loading">Loading</div>
            <div class="error-message"></div>
            <div class="sent-message">Your notification request was sent. Thank you!</div>
          </div>
        </form>
      </div>

    </div>
  </header><!-- End #header -->

  <main id="main">

    <!-- ======= About Us Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="row content">
          <table class="table text-white">
            <thead class="thead-dark">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Comment</th>
                <th scope="col">Sentiment</th>
                <th scope="col">pourcentage</th>
              </tr>
            </thead>
            <tbody>
              <?php echo $comments; ?>
            </tbody>
          </table>
          
        </div>

      </div>
    </section><!-- End About Us Section -->

    <!-- ======= Contact Us Section ======= -->
    <section id="contact" class="contact">
      <div class="container">

        <div class="section-title">
          <h2>Resultat</h2>
        </div>

        <div class="row justify-content-center">

          <div class="col-lg-10">

            <div class="info-wrap">
              <div class="row">

                <div class="col-lg-4 info mt-4 mt-lg-0">
                  <span class="icone i1"></span>
                  <h4>Positive</h4>
                  <p><?php echo number_format($porPos, 2, '.', ''); ?></p>
                </div>

                <div class="col-lg-4 info">
                  <span class="icone i2"></span>
                  <h4>Neutral:</h4>
                  <p><?php echo number_format($porNat, 2, '.', ''); ?></p>
                </div>

                <div class="col-lg-4 info mt-4 mt-lg-0">
                  <span class="icone i3"></span>
                  <h4>Negative</h4>
                  <p><?php echo number_format($porNeg, 2, '.', ''); ?></p>
                </div>


              </div>
            </div>

          </div>

        </div>

      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>ComingSoon</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/comingsoon-free-html-bootstrap-template/ -->
        Designed by Test
      </div>
    </div>
  </footer><!-- End #footer -->

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/jquery-countdown/jquery.countdown.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>