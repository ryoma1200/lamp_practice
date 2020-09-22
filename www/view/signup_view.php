<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>サインアップ</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'signup.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header.php'; ?>
  <div class="container">
    <h1>ユーザー登録</h1>

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <form method="post" action="signup_process.php" class="signup_form mx-auto">
      <div class="form-group">
        <label for="name">名前: </label>
        <input type="text" name="name" id="name" class="form-control">
      </div>
      <div class="form-group">
        <label for="password">パスワード: </label>
        <input type="password" name="password" id="password" class="form-control">
      </div>
      <div class="form-group">
        <label for="password_confirmation">パスワード（確認用）: </label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
      </div>
      <input type="submit" value="登録" class="btn btn-primary">
      <input type="hidden" name="token" value="<?php print $token; ?>">
    </form>
  </div>
</body>
</html>