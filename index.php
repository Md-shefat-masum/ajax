<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Master in AJAX</title>
    <meta name="robots" content="follow,index">
    <meta name="image" content="https://md-shefat-masum.github.io/ajax/assets/images/ajax.gif">
    <meta name="Classification" content="programming">
    <meta name="identifier-URL" content="https://md-shefat-masum.github.io/ajax/">
    <meta name="directory" content="submission">
    <meta name="category" content="Internet">
    <meta name="coverage" content="Worldwide">
    <meta name="distribution" content="Global">
    <meta name="rating" content="General">
    <meta name="target" content="all">
    <meta name="HandheldFriendly" content="True">
    <meta name="author" content="md-shefat-masum">
    <meta name="developer" content="md-shefat-masum">
    <meta name="developer-company" content="hungrycoder">
    <meta name="developer-email" content="mshefat924@gmail.com">
    <meta name="contact" content="Contact me - +8801646376015">
    <meta name="copyright" content="https://md-shefat-masum.github.io/ajax">

    <meta name="keywords" content="ajax, laravel-ajax, php-ajax, javacript-ajax, how to, what is, where is, who is, when was, define, explain, translate, find, lookup, search, locate, identify, compare, contrast, analyze, categorize, sort, filter, paginate">
    <meta name="description" content="Learn how to use Ajax to create interactive web applications that load data without reloading the page. ">

    <meta property="og:title" content="master in AJAX" />
    <meta property="og:site_name" content="master in AJAX" />
    <meta property="og:description" content="Learn how to use Ajax to create interactive web applications that load data without reloading the page." />
    <meta property="og:type" content="Programming" />
    <meta property="og:url" content="https://md-shefat-masum.github.io/ajax" />
    <meta property="og:image" content="https://md-shefat-masum.github.io/ajax/assets/images/ajax.gif" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />

    <meta name="twitter:title" content="master in AJAX">
    <meta name="twitter:description" content="Learn how to use Ajax to create interactive web applications that load data without reloading the page.">
    <meta name="twitter:image" content="https://md-shefat-masum.github.io/ajax/assets/images/ajax.gif">
    <meta name="twitter:card" content="summary_large_image">

    <link href="./assets/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
    <script src="./assets/plugins/bootstrap/popper.min.js"></script>
    <script src="./assets/plugins/bootstrap/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./assets/styles/styles.css">

</head>

<body>
    <nav class="navbar navbar-expand-lg shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <section id="banner">
        <div class="container">
            <h1 class="shadow-sm">AJAX</h1>
        </div>
    </section>
    <section>
        <div class="container mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>All Data</h4>
                    <div>
                        <button class="btn btn-secondary btn-sm" data-bs-target="#exampleModalToggle" data-bs-toggle="modal">Create</button>
                    </div>
                </div>
                <div class="card-body ">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0" style="width: calc(100% - 4px);">
                            <thead class="">
                                <tr>
                                    <th class="border-top ps-3">Name</th>
                                    <th class="border-top">Email</th>
                                    <th class="border-top">Age</th>
                                    <th class="text-end align-middle pe-3 border-top">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 0; $i < 10; $i++) {
                                ?>
                                    <tr>
                                        <td class="align-middle ps-3 name">Anna</td>
                                        <td class="align-middle email">anna@example.com</td>
                                        <td class="align-middle age">18</td>
                                        <td class="align-middle white-space-nowrap text-end pe-3">
                                            <div class="font-sans-serif btn-reveal-trigger position-static">
                                                <button class="btn btn-sm btn-outline-success dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent">
                                                    <i class="fa fa-align-right"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end py-2">
                                                    <a class="dropdown-item" href="#!">View</a>
                                                    <a class="dropdown-item" href="#!">Export</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="#!">Remove</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination mb-0 justify-content-center">
                            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <form action="">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalToggleLabel">CRUD Form</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="from-group mb-3">
                            <label for="">Full Name</label>
                            <input type="username" name="username" id="username" class="form-control">
                        </div>
                        <div class="from-group mb-3">
                            <label for="">Email</label>
                            <input type="email" name="username" id="username" class="form-control">
                        </div>
                        <div class="from-group mb-3">
                            <label for="">Birth Date</label>
                            <input type="date" name="dob" id="dob" onclick="" class="form-control">
                        </div>
                        <div class="from-group mb-3">
                            <label for="">User Role</label>
                            <select name="user_role" id="user_role" class="form-select">
                                <option value="admin">admin</option>
                                <option value="user">user</option>
                                <option value="customer">customer</option>
                            </select>
                        </div>
                        <div class="from-group mb-3">
                            <label for="">Gender</label> <br>
                            <label for="male">
                                <input id="male" value="male" name="gender" type="radio"  class="form-check-input"> Male <br>
                            </label>
                            <label for="female">
                                <input id="female" value="female" name="gender" type="radio"  class="form-check-input"> Female <br>
                            </label>
                        </div>
                        <div class="from-group mb-3">
                            <label for="">Courses</label> <br>
                            <label for="web_design">
                                <input id="web_design" value="web_design" name="gender" type="checkbox"  class="form-check-input"> web design <br>
                            </label>
                            <label for="graphics_design">
                                <input id="graphics_design" value="graphics_design" name="gender" type="checkbox"  class="form-check-input"> graphics design <br>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="button">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>