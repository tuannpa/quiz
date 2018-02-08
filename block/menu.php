<div class="menu">
    <nav class="navbar navbar-expand-lg navbar-light menu-edit">
        <a class="navbar-brand" href="index.php">Homepage</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="javascript:void(0)">Latest News<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Database
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="?page=result">Result</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="javascript:void(0)">User's Remark</a>
                    </div>
                </li>

                <?php if (!is_bool(AuthController::verifyToken($_COOKIE['token']))): ?>
                <li class="nav-item active">
                    <a class="nav-link" href="?page=quiz">Quiz <span class="sr-only">(current)</span></a>
                </li>
                <?php endif; ?>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search for articles.." aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0 search-btn" type="submit">Search</button>
            </form>
        </div>
    </nav>
</div>