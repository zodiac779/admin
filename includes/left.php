<?php 

$array = explode('/',$_SERVER['PHP_SELF']);
$count = count($array);
$pagename = $array[$count-2];
$currentPage = 'active';

mysql_select_db($database_conn_database, $conn_database);
$query_rs_setting = "SELECT * FROM setting WHERE id = 1;";
$rs_setting = mysql_query($query_rs_setting, $conn_database) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);
?>
<div class="page-sidebar navbar-collapse collapse">
<!-- BEGIN SIDEBAR MENU -->
<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
<ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
    <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
    <li class="sidebar-toggler-wrapper hide">
        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        <div class="sidebar-toggler"></div>
        <!-- END SIDEBAR TOGGLER BUTTON -->
    </li>
    <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
    <li class="sidebar-search-wrapper">
        <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
        <!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
        <!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
    </li>
        <!-- END RESPONSIVE QUICK SEARCH FORM -->
    <li class="nav-item <?php if($pagename == "dashboard"){echo $currentPage;}?>">
        <a href="../dashboard/" class="nav-link">
            <i class="fa fa-bar-chart"></i>
            <span class="title">Dashboard</span>
        </a>
    </li>
    <li class="nav-item <?php if($pagename == "content-home" || $pagename == "content-about" || $pagename == "content-product" || $pagename == "content-service" || $pagename == "content-portfolio" || $pagename == "content-news" || $pagename == "content-contact" || $pagename == "content-footer"){echo $currentPage.' open';}?>">
        <a href="javascript:;" class="nav-link nav-toggle">
            <i class="fa fa-book"></i>
            <span class="title">Content</span>
            <span class="selected"></span>
            <span class="arrow <?php if($pagename == "content-home" || $pagename == "content-about" || $pagename == "content-product" || $pagename == "content-service" || $pagename == "content-portfolio" || $pagename == "content-news" || $pagename == "content-contact" || $pagename == "content-footer"){echo 'open';}?>"></span>
        </a>
        <ul class="sub-menu">
            <li class=" <?php if($pagename == "content-home"){echo $currentPage;}?>">
                <a href="../content-home/">
                Home
                </a>
            </li>
            <li class=" <?php if($pagename == "content-about"){echo $currentPage;}?>">
                <a href="../content-about/">
                About Us
                </a>
            </li>
            <li class=" <?php if($pagename == "content-product"){echo $currentPage;}?>">
                <a href="../content-product/">
                Products
                </a>
            </li>
            <li class=" <?php if($pagename == "content-service"){echo $currentPage;}?>">
                <a href="../content-service/">
                Services
                </a>
            </li>
            <li class=" <?php if($pagename == "content-portfolio"){echo $currentPage;}?>">
                <a href="../content-portfolio/">
                Portfolios
                </a>
            </li>
            <li class=" <?php if($pagename == "content-news"){echo $currentPage;}?>">
                <a href="../content-news/">
                News & Events
                </a>
            </li>
            <li class=" <?php if($pagename == "content-contact"){echo $currentPage;}?>">
                <a href="../content-contact/">
                Contact Us
                </a>
            </li>
            <li class=" <?php if($pagename == "content-footer"){echo $currentPage;}?>">
                <a href="../content-footer/">
                Footer
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item <?php if($pagename == "about_gallery" || $pagename == "team"){echo $currentPage.' open';}?>">
        <a href="javascript:;" class="nav-link nav-toggle">
            <i class="fa fa-tags"></i>
            <span class="title">About Us</span>
            <span class="selected"></span>
            <span class="arrow <?php if($pagename == "about_gallery"){echo 'open';}?>"></span>
        </a>
        <ul class="sub-menu">
            <li class="<?php if($pagename == "about_gallery"){echo $currentPage;}?>">
                <a href="../about_gallery/">
                About Us Gallery
                </a>
            </li>
            <li class="<?php if($pagename == "team"){echo $currentPage;}?>">
                <a href="../team/">
                Team
                </a>
            </li>            
        </ul>
    </li>
    <li class="nav-item <?php if($pagename == "testimonial" || $pagename == "content-testimonial"){echo $currentPage.' open';}?>">
        <a href="javascript:;" class="nav-link nav-toggle">
            <i class="fa fa-certificate"></i>
            <span class="title">Testimonial</span>
            <span class="selected"></span>
            <span class="arrow <?php if($pagename == "testimonial" || $pagename == "content-testimonial"){echo 'open';}?>"></span>
        </a>
        <ul class="sub-menu">
            <li class=" <?php if($pagename == "testimonial"){echo $currentPage;}?>">
                <a href="../testimonial/" >
                Testimonial  
                </a>
            </li>

             <li class="<?php if($pagename == "content-testimonial"){echo $currentPage;}?>">
                <a href="../content-testimonial/">
                Testimonial Image 
                </a>
            </li>
        </ul>
    </li> 
    <li class="nav-item <?php if($pagename == "product" || $pagename == "product_gallery" ){echo $currentPage.' open';}?>">
        <a href="javascript:;" class="nav-link nav-toggle">
            <i class="fa fa-cart-arrow-down"></i>
            <span class="title">Product</span>
            <span class="selected"></span>
            <span class="arrow <?php if($pagename == "product" || $pagename == "product_gallery" ){echo 'open';}?>"></span>
        </a>
        <ul class="sub-menu">
            <li class="<?php if($pagename == "product" || $pagename == "product_gallery"){echo $currentPage;}?>">
                <a href="../product/">
                Product Listing 
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item <?php if($pagename == "service" || $pagename == "service_gallery" ){echo $currentPage.' open';}?>">
        <a href="javascript:;" class="nav-link nav-toggle">
            <i class="fa fa-smile-o"></i>
            <span class="title">Service</span>
            <span class="selected"></span>
            <span class="arrow <?php if($pagename == "service" || $pagename == "service_gallery" ){echo 'open';}?>"></span>
        </a>
        <ul class="sub-menu">
            <li class="<?php if($pagename == "service" || $pagename == "service_gallery"){echo $currentPage;}?>">
                <a href="../service/">
                Service Listing 
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item <?php if($pagename == "portfolio" || $pagename == "portfolio_gallery" ){echo $currentPage.' open';}?>">
        <a href="javascript:;" class="nav-link nav-toggle">
            <i class="fa fa-trophy"></i>
            <span class="title">Portfolio</span>
            <span class="selected"></span>
            <span class="arrow <?php if($pagename == "portfolio" || $pagename == "portfolio_gallery" ){echo 'open';}?>"></span>
        </a>
        <ul class="sub-menu">
            <li class="<?php if($pagename == "portfolio" || $pagename == "portfolio_gallery"){echo $currentPage;}?>">
                <a href="../portfolio/">
                Portfolio Listing 
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item <?php if($pagename == "news"){echo $currentPage;}?>">
        <a href="../news/" class="nav-link">
            <i class="fa fa-newspaper-o"></i>
            <span class="title">News & Events</span>
        </a>
    </li>
    <li class="nav-item <?php if($pagename == "clients_logo"){echo $currentPage;}?>">
        <a href="../clients_logo/" class="nav-link">
            <i class="fa fa-gg-circle"></i>
            <span class="title">Clients Logo</span>
        </a>
    </li> 
    <li class="nav-item <?php if($pagename == "subscribe"){echo $currentPage;}?>">
        <a href="../subscribe/" class="nav-link">
            <i class="fa fa-suitcase"></i>
            <span class="title">Subscribe</span>
        </a>
    </li>
    <li class="nav-item <?php if($pagename == "contact"){echo $currentPage;}?>">
        <a href="../contact/" class="nav-link">
            <i class="fa fa-archive"></i>
            <span class="title">Contact</span>
        </a>
    </li>
    <li class="nav-item <?php if($pagename == "setting-main"){echo $currentPage;}?>">
        <a href="../setting-main/" class="nav-link">
            <i class="fa fa-cogs"></i>
            <span class="title">Setting</span>
        </a>
    </li> 
    <li class="nav-item <?php if($pagename == "mail-server"){echo $currentPage;}?>">
        <a href="../mail-server/" class="nav-link">
            <i class="fa fa-database"></i>
            <span class="title">Mail Server</span>
        </a>
    </li> 
    <li class="nav-item <?php if($pagename == "menu_control"){echo $currentPage;}?>">
        <a href="../menu_control/" class="nav-link">
            <i class="fa fa-list-ul"></i>
            <span class="title">Menu Control</span>
        </a>
    </li>
    <li class="nav-item <?php if($pagename == "languages"){echo $currentPage;}?>">
    <a href="../languages/" class="nav-link">
        <i class="fa fa-language"></i>
        <span class="title">Languages</span>
    </a>
    </li>
    <li class="nav-item <?php if($pagename == "modules"){echo $currentPage;}?>">
    <a href="../modules/" class="nav-link">
        <i class="fa fa-star-o"></i>
        <span class="title">Modules</span>
    </a>
    </li>
</ul>
            
</div>

<?php mysql_free_result($rs_setting); ?>