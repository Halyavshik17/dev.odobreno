<nav class="navbar-custom-menu navbar navbar-expand-lg m-0 border-bottom shadow-none">
    <div class="sidebar-toggle-icon" id="sidebarCollapse">
        sidebar toggle<span></span>
    </div>
    <!--/.sidebar toggle icon-->
    <div class="navbar-icon d-flex">
        <ul class="navbar-nav flex-row align-items-center ">
            {{-- <li class="nav-item dropdown language-menu notification  me-3">
                <a class="language-menu_item border rounded-circle d-flex justify-content-center align-items-center overflow-hidden"
                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                    <img src=" {{ getLocalizeLang()->where('code', app()->getLocale())?->first()?->flag_url ?? '' }}">
                </a>
                <div class="dropdown-menu language_dropdown">
                    @foreach (getLocalizeLang() as $language)
                        <a href="{{ route('lang.switch', $language->code) }}"
                            class="language_item d-flex align-items-center gap-3">
                            <img src="{{ $language->flag_url }}">
                            <span>
                                {{ $language->title }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </li> --}}
            <li class="nav-item dropdown user_profile me-2">
                <a class="dropdown-toggle user_profile_item border rounded-circle  d-flex justify-content-center align-items-center overflow-hidden"
                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img class='img-fluid' src="{{ auth()->user()->profile_photo_url }}" alt="">
                </a>
                <div class="dropdown-menu">
                    <div class="d-flex align-items-center gap-3 border-bottom pb-3">
                        <div class="user_img">
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="">
                        </div>
                        <div>
                            <p class="mb-0 fw-bold fs-16">
                                {{ auth()->user()->name ?? '' }}
                            </p>
                            <p class="mb-0 text-muted fs-14">
                                {{ auth()->user()->email ?? '' }}
                            </p>
                        </div>
                    </div>

                    <ul class="list-unstyled mt-3  dropdown_menu_inner">
                        <li class="">
                            <a class="d-block"
                                href="{{ route('user-profile-information.index') }}">{{ localize('My Profile') }}</a>
                        </li>
                        <li class="">
                            <a class="d-block"
                                href="{{ route('user-profile-information.edit') }}">{{ localize('Edit Profile') }}</a>
                        </li>
                        <li class="">
                            <a class="d-block"
                                href="{{ route('user-profile-information.general') }}">{{ localize('Account Settings') }}</a>
                        </li>
                        <x-logout class="btn_sign_out text-black w-auto">{{ localize('Sign Out') }}</x-logout>

                    </ul>


                </div>
                <!--/.dropdown-menu -->
            </li>
        </ul>
        <!--/.navbar nav-->

    </div>
</nav>
