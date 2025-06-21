    <?php require_once 'app/views/templates/header.php'; ?>

    <div class="container">
        <div class="page-header" id="banner">
            <div class="row">
                <div class="col-lg-12">
                    
                    <?php if (!empty($_SESSION['username'])): ?>
                        <h1>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>
                    <?php else: ?>
                        <h1>Hey</h1>
                    <?php endif; ?>
                    <p class="lead"><?= date("F jS, Y"); ?></p>
                </div>
            </div>
        </div>

        
        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['flash_success']); ?>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-12">
                <p><a href="/logout">Click here to logout</a></p>
            </div>
        </div>
    </div>

    <?php require_once 'app/views/templates/footer.php'; ?>
