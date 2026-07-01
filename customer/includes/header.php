<div class="header">

  <div>

    <h1>
      Welcome Back,
      <?php echo htmlspecialchars($_SESSION['name']); ?>
      👋
    </h1>

    <p>
      Here's what's happening with your account today.
    </p>

  </div>

  <div class="user">

    <div class="avatar">
      <?php echo strtoupper(substr($_SESSION['name'], 0, 1)); ?>
    </div>

    <div>

      <strong>
        <?php echo htmlspecialchars($_SESSION['name']); ?>
      </strong>

      <br>

      Customer

    </div>

  </div>

</div>