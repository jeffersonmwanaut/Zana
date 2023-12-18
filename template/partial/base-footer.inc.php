    <div class="row justify-content-center bg-light mt-5">
        <div class="col">
            <div class="p-3 p-sm-5">
                <div class="row">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
                    <div class="col pe-5">
                        <h2 class="h5">JMM Corporation</h2>
                        <div class="small">
                            <p>
                                We provide wide range of services.
                                Our mission is to provide high-quality services to our customers and exceed their expectations.
                            </p>
                            <p>Democratic Republic of the Congo</p>
                        </div>
                    </div>

                        <div class="col pe-5">
                            <h2 class="h5">Links</h2>
                            <ul class="list-unstyled">
                                <li class="mb-2"><a class="link-underline link-underline-opacity-0" href="<?= URL_ROOT . '/' . $router::generateUrl('_MAIN') ?>">Home</a></li>
                                <li class="mb-2"><a class="link-underline link-underline-opacity-0" href="<?= URL_ROOT . '/' . $router::generateUrl('ABOUT.OVERVIEW') ?>">About</a></li>
                                <li class="mb-2"><a class="link-underline link-underline-opacity-0" href="<?= URL_ROOT . '/' . $router::generateUrl('CONTACT') ?>">Contact</a></li>
                                <li class="mb-2"><a class="link-underline link-underline-opacity-0" href="#">Privacy Policy</a></li>
                                <li class="mb-2"><a class="link-underline link-underline-opacity-0" href="#">Terms of Use</a></li>
                                <li class="mb-2"><a class="link-underline link-underline-opacity-0" href="#">Cookies Policy</a></li>
                            </ul>
                        </div>
                        
                        <div class="col col-md-12 pe-5">
                            <h2 class="h5">Follow Us</h2>
                            <ul class="list-inline fs-3">
                                <li class="list-inline-item"><a class="text-jmm" href="#"><i class="fab fa-linkedin fa-lg"></i></a></li>
                                <li class="list-inline-item"><a class="text-jmm" href="#"><i class="fab fa-facebook-square fa-lg"></i></a></li>
                                <li class="list-inline-item"><a class="text-jmm" href="#"><i class="fab fa-twitter-square fa-lg"></i></a></li>
                                <li class="list-inline-item"><a class="text-jmm" href="#"><i class="fab fa-youtube-square fa-lg"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="text-center mt-5">&copy; JMM Corporation 2023</p>
                        <p class="text-center">Free Stock photos by <a class="link-underline link-underline-opacity-0" href="https://www.vecteezy.com/free-photos">Vecteezy</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JQUERY -->
    <script type="text/javascript" src="<?= JQUERY ?>"></script>
    <!-- BOOTSTRAP JS CORE -->
    <script type="text/javascript" src="<?= BOOTSTRAP['js']['core'] ?>"></script>
    <!-- Bootstrap JS Tooltips -->
    <script type="text/javascript" src="<?= BOOTSTRAP['js']['popper'] ?>"></script>
    <!-- Bootstrap JS Material Design -->
    <script type="text/javascript" src="<?= BOOTSTRAP['js']['md'] ?>"></script>
    <script src="<?= JS ?>/app.js"></script>
</body>
</html>