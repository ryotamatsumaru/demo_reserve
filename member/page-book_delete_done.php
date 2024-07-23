<?php
//     Template Name: book_delete_done
//     Template Post Type: page
//     Template Path: member/

session_start();
session_regenerate_id(true);
if(isset($_SESSION['cus_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/login_caution_text/');
  exit();
}

require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');

if(isset($_POST['id'])){

  Measure::cus_validate();

  foreach($_POST['id'] as $id){
    $id = Measure::h($id);
    $dbh = Database::getPdo();
    $sql = "INSERT INTO cancel(id, mail, roma, name, tel, gender, number, night, day, price, type, reserve_date, random_id) SELECT id, mail, roma, name, tel, gender, number, night, day, price, type, reserve_date, random_id FROM booking WHERE id = :id ";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':id', $id, PDO::PARAM_INT);
    $ps->execute();
  }

  foreach($_POST['id'] as $id){
    $sql = "DELETE FROM booking WHERE id = :id ";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':id', $id, PDO::PARAM_INT);
    $ps->execute();
  }

  foreach(array_map(null, $_POST['id'], $_POST['row_night'], $_POST['reserve'] , $_POST['random']) as [$id, $night, $reserve, $random]){

  $night = Measure::h($night);
  $reserve = Measure::h($reserve);
  $random = Measure::h($random);

  $count = count($_POST['id']);
  $reduce_night = $night - $count;
  $sql = "UPDATE booking SET night = :night WHERE reserve_date = :reserve AND random_id = :random";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':night', $reduce_night, PDO::PARAM_INT);
  $ps->bindValue(':reserve', $reserve, PDO::PARAM_STR);
  $ps->bindValue(':random', $random, PDO::PARAM_INT);
  $ps->execute();
  }

  header('Location:https://ro-crea.com/demo_hotel/book_delete_done');
  exit();
}
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>デモサイト(ホテル)</title>
  <?php wp_head(); ?>
</head>

<body>
  <header id="header">
    <div class="flex wrapper">
      <h1 class="logo-title">
        <a href="<?php echo esc_url(home_url('#')); ?>">
          <img src="<?php echo esc_url(get_theme_file_uri('img/logo3.png')); ?>" alt="logo" class="logo">
          <span class="title">Hotel&city group</span>
        </a>
      </h1>
      <div class="right">
        <a href="#" class="login-btn">ログイン</a>
        <a href="#" class="book-btn"><span>宿泊予約</span></a>
        <div class="hamburger">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
    </div>

    <nav id="sp-navi">
      <ul class="back-list">
        <li><a href="http://demohotel.local"><span class="en">Top</span>トップページ</a></li>
        <li><a href="http://demohotel.local/concept"><span class="en">Concept</span>当館の特徴</a></li>
        <li><a href="http://demohotel.local/fasility"><span class="en">Fasility</span>施設案内</a></li>
        <li><a href="http://demohotel.local/restaurant"><span class="en">Restaurant</span>レストラン</a></li>
        <li><a href="http://demohotel.local/roomlist"><span class="en">Room Type</span>お部屋タイプ</a></li>
        <li><a href="http://demohotel.local/#photo"><span class="en">Gallery</span> フォトギャラリー</a></li>
        <li><a href="http://demohotel.local/#news"><span class="en">News</span>お知らせ</a></li>
        <li><a href="http://demohotel.local/member"><span class="en">Membership</span>メンバーシップ</a></li>
        <li><a href="http://demohotel.local"><span class="en">Contact</span>お問い合せ</a></li>
      </ul>
    </nav>

    <nav id="navi">
      <ul class="navi-area wrapper">
        <li><a href="http://demohotel.local"><span class="en">Top</span>
            <p class="ja">トップページ</p>
          </a></li>
        <li><a href="http://demohotel.local/concept"><span class="en">Concept</span>
            <p class="ja">当館の特徴</p>
          </a></li>
        <li><a href="http://demohotel.local/fasility"><span class="en">Fasility</span>
            <p class="ja">施設案内</p>
          </a></li>
        <li><a href="http://demohotel.local/restaurant"><span class="en">Restaurant</span>
            <p class="ja">レストラン</p>
          </a></li>
        <li><a href="http://demohotel.local/roomlist"><span class="en">Room Type</span>
            <p class="ja">お部屋一覧</p>
          </a></li>
        <li><a href="http://demohotel.local/#news"><span class="en">News</span>
            <p class="ja">お知らせ</p>
          </a></li>
        <li><a href="http://demohotel.local/member"><span class="en">Member</span>
            <p class="ja">会員制度</p>
          </a></li>
      </ul>
    </nav>
  </header>
<section>
  <?php if(!empty($_SESSION['confirm3'])):?>
    <div id="book-delete-done" class="wrapper">
      <p class="done-text">予約をキャンセルしました。</p>
      <a href="https://ro-crea.com/demo_hotel/book_detail" class="back">メニューに戻る</a>
    </div>
    <?php unset($_SESSION['confirm3']) ?>
    <?php unset($_SESSION['cus_token']) ?>
  <?php else: ?>
    <div id="invalid" class="wrapper">
      <p class="caution">不正な画面遷移です。</p>
      <a href="https://ro-crea.com/demo_hotel/menu/">メニューに戻る</a>
    </div>
  <?php endif;?>
</section>
<?php get_footer("3"); ?>