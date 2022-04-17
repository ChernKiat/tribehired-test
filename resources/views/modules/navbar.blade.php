<nav class="navbar fixed-top align-items-start navbar-expand-lg pl-0 pr-0 py-0" >

    <div class="navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand mr-0" href="{{-- route('main') --}}">
            <img src="https://mttsolutions.com.my/img/logo.png" class="logo-lg" width="140" />
            <img src="https://mttsolutions.com.my/img/logo.png" class="logo-sm" width="140" />
        </a>
    </div>

    <div>
        <button type="button" id="sidebar-toggle" data-target="#sidebar-nav">
            <small>Navigation</small>
            <i class="fas fa-align-right text-muted"></i>
        </button>

        <button class="navbar-toggler mr-0"
                type="button"
                data-toggle="collapse"
                data-target="#top-navigation"
                aria-controls="top-navigation"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <small>User Menu</small>
            <i class="fas fa-bars text-muted"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="top-navigation">
        <div class="row ml-2">
            <div class="col-lg-12 d-flex align-items-left align-items-md-center flex-column flex-md-row py-3">
                <h4 class="page-header mb-0">
                    @yield('page-heading')
                </h4>

                <ol class="breadcrumb mb-0 font-weight-light">
                    <li class="breadcrumb-item">
                        <a href="{{-- route('main') --}}" class="text-muted">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>

                    @yield('breadcrumbs')
                </ol>
            </div>
        </div>

        <ul class="navbar-nav ml-auto pr-3 flex-row">
            @hook('navbar:items')

            <li class="nav-item d-flex align-items-center visible-lg">
                @if (false && $user->hasPermission('support.sendemail'))
                    <a class="btn text-danger" data-toggle="modal" data-target="#supportModal" onclick="support_form()">
                        <i class="fas fa-question-circle"></i>
                        @lang('Support')
                    </a>
                @endif
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle"
                   href="#"
                   id="navbarDropdown"
                   role="button"
                   data-toggle="dropdown"
                   aria-haspopup="true"
                   aria-expanded="false">
                    <img src="{{ false && $user->present()->avatar }}"
                         width="50"
                         height="50"
                         class="rounded-circle img-thumbnail img-responsive">
                </a>
                <div class="dropdown-menu dropdown-menu-right position-absolute p-0" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item py-2" href="{{-- route('profile') --}}">
                        <i class="fas fa-user text-muted mr-2"></i>
                        @lang('My Profile')
                    </a>

                    @if (config('session.driver') == 'database')
                        <a href="{{-- route('profile.sessions') --}}" class="dropdown-item py-2">
                            <i class="fas fa-list text-muted mr-2"></i>
                            @lang('Active Sessions')
                        </a>
                    @endif

                    @hook('navbar:dropdown')

                    <div class="dropdown-divider m-0"></div>

                    <a class="dropdown-item py-2" href="{{-- route('auth.logout') --}}">
                        <i class="fas fa-sign-out-alt text-muted mr-2"></i>
                        @lang('Logout')
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<div id="supportModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#179970; color: white;>
                <h4 class="modal-title" style="color:white;"><strong>@lang('Contact Support')</strong></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post" action="{{-- route('support.sendemail') --}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="item_name">Email Address (Email Of Who Is Requesting This Support)</label>
                                <input type="text" class="form-control input-solid" id="email_address"
                                    name="email_address" value="">
                            </div>

                            <div class="form-group">
                                <label for="item_sku">Contact No. (Contact No. Of Who Is Requesting This Support)</label>
                                <input type="text" class="form-control input-solid" id="contact_no"
                                    name="contact_no" value="">
                            </div>

                            <div class="form-group">
                                <label for="item_width">@lang('Subject') (Support Title/What Is The Problem)</label>
                                <input type="text" class="form-control input-solid" id="subject"
                                    name="subject" value="">
                            </div>

                            <div class="form-group">
                                <label for="item_height">@lang('Description') (Explain The Problem)</label>
                                <textarea type="text" class="form-control input-solid" id="description"
                                    name="description" value=""> </textarea>
                            </div>

                            <div class="form-group" style="display: none;">
                                <label for="item_sku">Company Name</label>
                                <input type="text" class="form-control input-solid" id="company_name"
                                    name="company_name" value="">
                            </div>

                            <!-- <div class="form-group">
                                <label for="screenshot">@lang('Screenshot') </label>
                                <input type="file" class="form-control input-solid" id="screenshot" accept="image/*;capture=camera" picture-type="browseFiles"
                                    name="screenshot"> </input>
                            </div>    -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn" style="background-color:#179970; color: white;">@lang('Send')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function support_form()
    {
        // showLoading();
        $.ajax({
            type: "GET",
            url: "{{-- route('support.form') --}}",
            data: {
                _token  : $('input[name=_token]').val()
            },
            success: function(response) {
                $('#email_address').val(response['data']['email']);
                $('#contact_no').val(response['data']['contact_no']);
                $('#company_name').val(response['data']['company_name']);
            },
            complete: function (data) {
                // hideLoading();
            }
        });
    }
</script>
