<div class="topnav">
                    <div class="container-fluid">
                        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
    
                            <div class="collapse navbar-collapse" id="topnav-menu-content">
                                <ul class="navbar-nav">

                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-home-circle mr-2"></i>Notifications <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-dashboard">

                                            <a href="{{ url('noti-list')}}" class="dropdown-item">Notifications List</a>
                                            <a href="{{ url('send-noti')}}" class="dropdown-item">Send Notification</a>
                                            
                                        </div>
                                    </li>
    
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-uielement" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-tone mr-2"></i>UI Elements <div class="arrow-down"></div>
                                        </a>

                                        <div class="dropdown-menu"
                                            aria-labelledby="topnav-uielement">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div>
                                                        <a href="ui-alerts.html" class="dropdown-item">Alerts</a>
                                                        <a href="ui-buttons.html" class="dropdown-item">Buttons</a>
                                                        <a href="ui-cards.html" class="dropdown-item">Cards</a>
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
    
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-pages" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-customize mr-2"></i>User Management <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-pages">

                                            <a href="{{url('user-list')}}" class="dropdown-item">User List</a>
                                            

                                            <a href="{{url('brand-list')}}" class="dropdown-item">Brand List</a>
                                            <a href="{{url('add-brand')}}" class="dropdown-item">Add Brand</a>

                                        </div>
                                    </li>
    
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-collection mr-2"></i>Order Management <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-pages">
                                            <a href="{{url('orders')}}" class="dropdown-item">Orders</a>
                                        </div>
                                    </li>
    
                        
    
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-file mr-2"></i>Miscellaneous <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                                            <div class="dropdown">
                                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-invoice"
                                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Category <div class="arrow-down"></div>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="topnav-invoice">
                                                    <a href="{{url('category-list')}}" class="dropdown-item">Category List</a>
                                                    <a href="{{url('add-category')}}" class="dropdown-item">Add Category</a>
                                                </div>
                                            </div>
                                            <div class="dropdown">
                                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-auth"
                                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Products <div class="arrow-down"></div>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                    <a href="{{url('add-product')}}" class="dropdown-item">Add Product</a>
                                                    <a href="{{url('product-list')}}" class="dropdown-item">Product List</a>
                                                    
                                                </div>
                                            </div>
                                            <div class="dropdown">
                                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility"
                                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Pincode <div class="arrow-down"></div>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="topnav-utility">
                                                    <a href="{{url('pincode-list')}}" class="dropdown-item">Pincode List</a>
                                                    <a href="{{url('add-pincode')}}" class="dropdown-item">add Pincode</a>
                                                </div>
                                            </div>

                                            <div class="dropdown">
                                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Flags Master <div class="arrow-down"></div>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="topnav-utility">
                                                    <a href="{{url('flag-list')}}" class="dropdown-item">Flag List</a>
                                                    <a href="{{url('add-flag')}}" class="dropdown-item">add Flag</a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-layout mr-2"></i>Offers <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
                                            <a href="layouts-vertical.html" class="dropdown-item">Add Coupons</a>
                                            <a href="layouts-topbar-light.html" class="dropdown-item">Coupen List</a>
                                           
                                        </div>
                                    </li>
    
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>