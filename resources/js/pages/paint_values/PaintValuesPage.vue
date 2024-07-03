<template>
    <div>
        <div v-if="loading || filterLoader">
            <loader></loader>
        </div>
        <div class="dashboard_content_area">
            <div class="row">
                <div class="col-sm-6 col-8">
                    <h1 class="h4 heading_text">Paint Colors</h1>
                </div>
                <Navigation></Navigation>

            </div>
            <div id="success-alert" class="alert alert-success" v-if="successmsg !== ''">
                <strong>{{ successmsg }}</strong>
            </div>

            <div class="alert alert-danger" v-if="deletemsg !== ''">
                <strong>{{ deletemsg }}</strong>
            </div>
            <div class="add___scroll paint__values_client ">
                <div class="smp__tabs">
                    <div class="smp__tabs_ul_wrap">
                        <ul class="nav nav-tabs " id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button @click="activeTab('#home-tab', '#paint_colors')" class="nav-link" id="home-tab"
                                    data-bs-toggle="tab" data-bs-target="#paint_colors" type="button" role="tab"
                                    aria-controls="home" aria-selected="false">Paint Colors
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button @click="activeTab('#profile-tab', '#paint_finishings')" class="nav-link"
                                    id="profile-tab" data-bs-toggle="tab" data-bs-target="#paint_finishings" type="button"
                                    role="tab" aria-controls="profile" aria-selected="false">Paint Finishes
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button @click="activeTab('#li_p_g', '#paint_grades')" class="nav-link" id="li_p_g"
                                    data-bs-toggle="tab" data-bs-target="#paint_grades" type="button" role="tab"
                                    aria-controls="profile" aria-selected="false">Paint grades
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button @click="activeTab('#li_p_man', '#paint_manufacturers')" class="nav-link"
                                    id="li_p_man" data-bs-toggle="tab" data-bs-target="#paint_manufacturers" type="button"
                                    role="tab" aria-controls="profile" aria-selected="false">Paint Manufacturers
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button @click="activeTab('#li_p_me', '#paint_methods')" class="nav-link" id="li_p_me"
                                    data-bs-toggle="tab" data-bs-target="#paint_methods" type="button" role="tab"
                                    aria-controls="profile" aria-selected="false">Paint Methods
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button @click="activeTab('#li_p_su', '#paint_surfaces')" class="nav-link" id="li_p_su"
                                    data-bs-toggle="tab" data-bs-target="#paint_surfaces" type="button" role="tab"
                                    aria-controls="profile" aria-selected="false">Paint Surfaces
                                </button>
                            </li>

                        </ul>
                    </div>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade" id="paint_colors" role="tabpanel" aria-labelledby="home-tab">
                            <!-- ================ -->
                            <div class="table_area">
                                <div class="table_area_head">
                                    <h5 class="font_weight_600 font_family_Montserrat text_color_orange">Paint Colors</h5>
                                </div>
                                <div class="custom__search">
                                    <div class="row align-items-center">
                                        <div class="col-sm-6">
                                            <SingleFieldSearch @changetable="getPaintColorsSearch()"
                                                :table_data="table_paint_colors"></SingleFieldSearch>
                                        </div>
                                        <div class="col-sm-6 text-sm-end">
                                            <button type="button"
                                                class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10"
                                                data-bs-toggle="modal" data-bs-target="#PaintColorAdd">Add new</button>
                                        </div>
                                    </div>
                                </div>
                                <div id="paint_colors_table" class="no_more_tables table_to_be_used mg_top_1rem">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <!--<th class="min_max_80">Id</th>-->
                                                <th class="header-sort" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Color #</th>
                                                <th class="header-sort" :class="[sortBy == 'name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('name')">Paint Color</th>
                                                <!--<th>Hex</th>-->
                                                <th class="min_max_155 text_end_only_desktop">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="paint_colors.length > 0">
                                            <tr v-if="!loading" v-for="item in paint_colors" :key="item.id">
                                                <td data-title="Color #">{{ item.id }}</td>
                                                <td data-title="Color Name">{{ item.name }}</td>
                                                <!--<td data-title="Color Value">
                                            <div  class="chosen_color" style="" :style="{ backgroundColor: item.hex_value }">  </div>
                                            <div  class="chosen_value">{{ item.hex_value }}</div>
                                        </td>-->
                                                <td data-title="Action" class="min_max_155 text_end_only_desktop">
                                                    <!--                                            <a href="#"-->
                                                    <!--                                            class="table_btn_link font_12 font_weight_700 text_uppercase"-->
                                                    <!--                                            data-bs-toggle="modal" data-bs-target="#PaintColorEdit"-->
                                                    <!--                                            @click="editIdMethod('paintcolor',item.id)">Edit</a>-->

                                                    <!--                                            <a data-bs-toggle="modal" data-bs-target="#deleteModel"-->
                                                    <!--                                               class="table_btn_link font_12 font_weight_700 text_uppercase" href="#"-->
                                                    <!--                                               @click="deleteRequest(item.id,'Paint Color' , 'paint-color')">Delete </a>-->

                                                    <a href="#" id="dropdownMenuButton"
                                                        class="no_action custom-action-bar-icon" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" viewBox="0 0 16 16"
                                                            class="bi bi-three-dots-vertical no_action">
                                                            <path
                                                                d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <div class="dropdown-menu custom-action-dropdown"
                                                        aria-labelledby="dropdownMenuButton">
                                                        <a href="#"
                                                            class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                                            data-bs-toggle="modal" data-bs-target="#PaintColorEdit"
                                                            @click="editIdMethod('paintcolor', item.id)">Edit</a>
                                                        <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                                            class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                                            href="#"
                                                            @click="deleteRequest(item.id, 'Paint Color', 'paint-color')">Delete
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div v-if="!loading && paint_colors.length <= 0">
                                        <EmptySearch></EmptySearch>
                                    </div>
                                    <Pagination v-if="paint_colors.length > 0 && table_paint_colors.last_page > 1"
                                        @changetable="getPaintColors" :table_data="table_paint_colors"></Pagination>
                                </div>
                            </div>
                            <!-- =============== -->
                        </div>

                        <div class="tab-pane fade " id="paint_finishings" role="tabpanel" aria-labelledby="home-tab">
                            <!-- ================ -->
                            <div class="table_area">
                                <div class="table_area_head">
                                    <h5 class="font_weight_600 font_family_Montserrat text_color_orange">Paint Finishes</h5>
                                </div>
                                <div class="custom__search">
                                    <div class="row align-items-center">
                                        <div class="col-sm-6">
                                            <SingleFieldSearch @changetable="getPaintFinishingsSearch"
                                                :table_data="table_paint_finishings"></SingleFieldSearch>
                                        </div>
                                        <div class="col-sm-6 text-sm-end">
                                            <button type="button"
                                                class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10"
                                                data-bs-toggle="modal" data-bs-target="#PaintFinishingAdd">Add new
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="paint_finishings_table" class="no_more_tables table_to_be_used mg_top_1rem">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <!--<th class="min_max_80">Id</th>-->
                                                <th class="header-sort" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Finishing #</th>
                                                <th class="header-sort" :class="[sortBy == 'name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('name')">Paint Finishing</th>
                                                <th class="min_max_155 text_end_only_desktop">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="paint_finishings.length > 0">
                                            <tr v-if="!loading" v-for="item in paint_finishings" :key="item.id">
                                                <td data-title="Finishing #">{{ item.id }}</td>
                                                <td data-title="Color Name">{{ item.name }}</td>
                                                <td data-title="Action" class="min_max_155 text_end_only_desktop">
                                                    <!--                                            <a href="#"-->
                                                    <!--                                            class="table_btn_link font_12 font_weight_700 text_uppercase"-->
                                                    <!--                                            data-bs-toggle="modal" data-bs-target="#PaintFinishingEdit"-->
                                                    <!--                                            @click="editIdMethod('paintfinishing',item.id)">Edit</a>-->

                                                    <!--                                            <a data-bs-toggle="modal" data-bs-target="#deleteModel"-->
                                                    <!--                                               class="table_btn_link font_12 font_weight_700 text_uppercase" href="#"-->
                                                    <!--                                               @click="deleteRequest(item.id,'Paint Finishing' , 'paint-finishing')">Delete </a>-->

                                                    <a href="#" id="dropdownMenuButton"
                                                        class="no_action custom-action-bar-icon" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" viewBox="0 0 16 16"
                                                            class="bi bi-three-dots-vertical no_action">
                                                            <path
                                                                d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <div class="dropdown-menu custom-action-dropdown"
                                                        aria-labelledby="dropdownMenuButton">
                                                        <a href="#"
                                                            class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                                            data-bs-toggle="modal" data-bs-target="#PaintFinishingEdit"
                                                            @click="editIdMethod('paintfinishing', item.id)">Edit</a>
                                                        <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                                            class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                                            href="#"
                                                            @click="deleteRequest(item.id, 'Paint Finishing', 'paint-finishing')">Delete
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div v-if="!loading && paint_finishings.length <= 0">
                                        <EmptySearch></EmptySearch>
                                    </div>
                                    <Pagination v-if="paint_finishings.length > 0 && table_paint_finishings.last_page > 1"
                                        @changetable="getPaintFinishings" :table_data="table_paint_finishings"></Pagination>

                                </div>
                            </div>
                            <!-- =============== -->
                        </div>

                        <div class="tab-pane fade " id="paint_grades" role="tabpanel" aria-labelledby="home-tab">
                            <!-- ================ -->
                            <div class="table_area">
                                <div class="table_area_head">
                                    <h5 class="font_weight_600 font_family_Montserrat text_color_orange ">
                                        Paint Grades</h5>
                                </div>
                                <div class="custom__search">
                                    <div class="row align-items-center">
                                        <div class="col-sm-6">
                                            <SingleFieldSearch @changetable="getPaintGradesSearch"
                                                :table_data="table_paint_grades"></SingleFieldSearch>
                                        </div>
                                        <div class="col-sm-6 text-sm-end">
                                            <button type="button"
                                                class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10"
                                                data-bs-toggle="modal" data-bs-target="#PaintGradeAdd">Add new
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="paint_grades_table" class="no_more_tables table_to_be_used mg_top_1rem">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <!--<th class="min_max_80">Id</th>-->
                                                <th class="header-sort" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Grade #</th>
                                                <th class="header-sort" :class="[sortBy == 'name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('name')">Paint Grade</th>
                                                <th class="min_max_155 text_end_only_desktop">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="paint_grades.length > 0">
                                            <tr v-if="!loading" v-for="item in paint_grades" :key="item.id">
                                                <td data-title="Grade #">{{ item.id }}</td>
                                                <td data-title="Color Name">{{ item.name }}</td>
                                                <td data-title="Action" class="min_max_155 text_end_only_desktop">
                                                    <!--                                            <a href="#"-->
                                                    <!--                                            class="table_btn_link font_12 font_weight_700 text_uppercase"-->
                                                    <!--                                            data-bs-toggle="modal" data-bs-target="#PaintGradeEdit"-->
                                                    <!--                                            @click="editIdMethod('paintgrade',item.id)">Edit</a>-->

                                                    <!--                                            <a data-bs-toggle="modal" data-bs-target="#deleteModel"-->
                                                    <!--                                               class="table_btn_link font_12 font_weight_700 text_uppercase" href="#"-->
                                                    <!--                                               @click="deleteRequest(item.id,'Paint Grade' , 'paint-grade')">Delete </a>-->

                                                    <a href="#" id="dropdownMenuButton"
                                                        class="no_action custom-action-bar-icon" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" viewBox="0 0 16 16"
                                                            class="bi bi-three-dots-vertical no_action">
                                                            <path
                                                                d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <div class="dropdown-menu custom-action-dropdown"
                                                        aria-labelledby="dropdownMenuButton">
                                                        <a href="#"
                                                            class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                                            data-bs-toggle="modal" data-bs-target="#PaintGradeEdit"
                                                            @click="editIdMethod('paintgrade', item.id)">Edit</a>
                                                        <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                                            class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                                            href="#"
                                                            @click="deleteRequest(item.id, 'Paint Grade', 'paint-grade')">Delete
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div v-if="!loading && paint_grades.length <= 0">
                                        <EmptySearch></EmptySearch>
                                    </div>
                                    <Pagination v-if="paint_grades.length > 0 && table_paint_grades.last_page > 1"
                                        @changetable="getPaintGrades" :table_data="table_paint_grades"></Pagination>

                                </div>
                            </div>
                            <!-- =============== -->
                        </div>

                        <div class="tab-pane fade " id="paint_manufacturers" role="tabpanel" aria-labelledby="home-tab">
                            <!-- ================ -->
                            <div class="table_area">
                                <div class="table_area_head">
                                    <h5 class="font_weight_600 font_family_Montserrat text_color_orange ">
                                        Paint Manufacturers</h5>
                                </div>
                                <div class="custom__search">
                                    <div class="row align-items-center">
                                        <div class="col-sm-6">
                                            <SingleFieldSearch @changetable="getPaintManufacturersSearch"
                                                :table_data="table_paint_manufacturers"></SingleFieldSearch>
                                        </div>
                                        <div class="col-sm-6 text-sm-end">
                                            <button type="button"
                                                class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10"
                                                data-bs-toggle="modal" data-bs-target="#PaintManufacturerAdd">Add new
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="paint_manufacturers_table" class="no_more_tables table_to_be_used mg_top_1rem">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="header-sort" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Manufacturer #</th>
                                                <th class="header-sort" :class="[sortBy == 'name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('name')">Paint Manufacturer</th>
                                                <th class="min_max_155 text_end_only_desktop">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="paint_manufacturers.length > 0">
                                            <tr v-if="!loading" v-for="item in paint_manufacturers" :key="item.id">
                                                <td data-title="Manufacturer #">{{ item.id }}</td>
                                                <td data-title="Color Name">{{ item.name }}</td>
                                                <td data-title="Action" class="min_max_155 text_end_only_desktop">

                                                    <!--                                            <a href="#"-->
                                                    <!--                                            class="table_btn_link font_12 font_weight_700 text_uppercase"-->
                                                    <!--                                            data-bs-toggle="modal" data-bs-target="#PaintManufacturerEdit"-->
                                                    <!--                                            @click="editIdMethod('paintmanufacturer',item.id)">Edit</a>-->

                                                    <!--                                            <a data-bs-toggle="modal" data-bs-target="#deleteModel"-->
                                                    <!--                                               class="table_btn_link font_12 font_weight_700 text_uppercase" href="#"-->
                                                    <!--                                               @click="deleteRequest(item.id,'Paint Manufacturer' , 'paint-manufacturer')">Delete </a>-->

                                                    <a href="#" id="dropdownMenuButton"
                                                        class="no_action custom-action-bar-icon" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" viewBox="0 0 16 16"
                                                            class="bi bi-three-dots-vertical no_action">
                                                            <path
                                                                d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <div class="dropdown-menu custom-action-dropdown"
                                                        aria-labelledby="dropdownMenuButton">
                                                        <a href="#"
                                                            class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                                            data-bs-toggle="modal" data-bs-target="#PaintManufacturerEdit"
                                                            @click="editIdMethod('paintmanufacturer', item.id)">Edit</a>
                                                        <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                                            class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                                            href="#"
                                                            @click="deleteRequest(item.id, 'Paint Manufacturer', 'paint-manufacturer')">Delete
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div v-if="!loading && paint_manufacturers.length <= 0">
                                        <EmptySearch></EmptySearch>
                                    </div>
                                    <Pagination
                                        v-if="paint_manufacturers.length > 0 && table_paint_manufacturers.last_page > 1"
                                        @changetable="getPaintManufacturers" :table_data="table_paint_manufacturers">
                                    </Pagination>

                                </div>
                            </div>
                            <!-- =============== -->
                        </div>

                        <div class="tab-pane fade " id="paint_methods" role="tabpanel" aria-labelledby="home-tab">
                            <!-- ================ -->
                            <div class="table_area">
                                <div class="table_area_head">
                                    <h5 class="font_weight_600 font_family_Montserrat text_color_orange ">
                                        Paint Methods</h5>
                                </div>
                                <div class="custom__search">
                                    <div class="row align-items-center">
                                        <div class="col-sm-6">
                                            <SingleFieldSearch @changetable="getPaintMethodsSearch"
                                                :table_data="table_paint_methods"></SingleFieldSearch>
                                        </div>
                                        <div class="col-sm-6 text-sm-end">
                                            <button type="button"
                                                class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10"
                                                data-bs-toggle="modal" data-bs-target="#PaintMethodAdd">Add new
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="paint_methods_table" class="no_more_tables table_to_be_used mg_top_1rem">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="header-sort" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Method #</th>
                                                <th class="header-sort" :class="[sortBy == 'name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('name')">Paint Method</th>
                                                <th class="min_max_155 text_end_only_desktop">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="paint_methods.length > 0">
                                            <tr v-if="!loading" v-for="item in paint_methods" :key="item.id">
                                                <td data-title="Method #">{{ item.id }}</td>
                                                <td data-title="Color Name">{{ item.name }}</td>
                                                <td data-title="Action" class="min_max_155 text_end_only_desktop">
                                                    <!--                                            <a href="#"-->
                                                    <!--                                            class="table_btn_link font_12 font_weight_700 text_uppercase"-->
                                                    <!--                                            data-bs-toggle="modal" data-bs-target="#PaintMethodEdit"-->
                                                    <!--                                            @click="editIdMethod('paintmethod',item.id)">Edit</a>-->

                                                    <!--                                            <a data-bs-toggle="modal" data-bs-target="#deleteModel"-->
                                                    <!--                                               class="table_btn_link font_12 font_weight_700 text_uppercase" href="#"-->
                                                    <!--                                               @click="deleteRequest(item.id,'Paint Method' , 'paint-method')">Delete </a>-->

                                                    <a href="#" id="dropdownMenuButton"
                                                        class="no_action custom-action-bar-icon" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" viewBox="0 0 16 16"
                                                            class="bi bi-three-dots-vertical no_action">
                                                            <path
                                                                d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <div class="dropdown-menu custom-action-dropdown"
                                                        aria-labelledby="dropdownMenuButton">
                                                        <a href="#"
                                                            class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                                            data-bs-toggle="modal" data-bs-target="#PaintMethodEdit"
                                                            @click="editIdMethod('paintmethod', item.id)">Edit</a>
                                                        <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                                            class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                                            href="#"
                                                            @click="deleteRequest(item.id, 'Paint Method', 'paint-method')">Delete
                                                        </a>

                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div v-if="!loading && paint_methods.length <= 0">
                                        <EmptySearch></EmptySearch>
                                    </div>
                                    <Pagination v-if="paint_methods.length > 0 && table_paint_methods.last_page > 1"
                                        @changetable="getPaintMethods" :table_data="table_paint_methods"></Pagination>

                                </div>
                            </div>
                            <!-- =============== -->
                        </div>

                        <div class="tab-pane fade " id="paint_surfaces" role="tabpanel" aria-labelledby="home-tab">
                            <!-- ================ -->
                            <div class="table_area">
                                <div class="table_area_head">
                                    <h5 class="font_weight_600 font_family_Montserrat text_color_orange">
                                        Paint Surfaces</h5>
                                </div>
                                <div class="custom__search">
                                    <div class="row align-items-center">
                                        <div class="col-sm-6">
                                            <SingleFieldSearch @changetable="getPaintSurfacesSearch"
                                                :table_data="table_paint_surfaces"></SingleFieldSearch>
                                        </div>
                                        <div class="col-sm-6 text-sm-end">
                                            <button type="button"
                                                class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10"
                                                data-bs-toggle="modal" data-bs-target="#PaintSurfaceAdd">Add new
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="paint_surfaces_table" class="no_more_tables table_to_be_used mg_top_1rem">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="header-sort" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Surface #</th>
                                                <th class="header-sort" :class="[sortBy == 'name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('name')">Paint Surface</th>
                                                <th  class="header-sort" :class="[sortBy == 'paint_method' && sortOrder == 'desc'  ? 'desc' : '']"   @click="sorting('paint_method')">Paint Method</th>
                                                <th class="min_max_155 text_end_only_desktop">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="paint_surfaces.length > 0">
                                            <tr v-if="!loading" v-for="item in paint_surfaces" :key="item.id">
                                                <td data-title="Surface #">{{ item.id }}</td>
                                                <td data-title="Color Name">{{ item.name }}</td>
                                                <td data-title="paint_method" v-if="item.paint_method">
                                                    {{ item.paint_method.name }}
                                                </td>
                                                <td data-title="paint_method" v-else>--</td>
                                                <td data-title="Action" class="min_max_155 text_end_only_desktop">
                                                    <!--                                            <a href="#"-->
                                                    <!--                                            class="table_btn_link font_12 font_weight_700 text_uppercase"-->
                                                    <!--                                            data-bs-toggle="modal" data-bs-target="#PaintSurfaceEdit"-->
                                                    <!--                                            @click="editIdMethod('paintsurface',item.id)">Edit</a>-->

                                                    <!--                                            <a data-bs-toggle="modal" data-bs-target="#deleteModel"-->
                                                    <!--                                               class="table_btn_link font_12 font_weight_700 text_uppercase" href="#"-->
                                                    <!--                                               @click="deleteRequest(item.id,'Paint Surface' , 'paint-surface')">Delete </a>-->

                                                    <a href="#" id="dropdownMenuButton"
                                                        class="no_action custom-action-bar-icon" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" viewBox="0 0 16 16"
                                                            class="bi bi-three-dots-vertical no_action">
                                                            <path
                                                                d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <div class="dropdown-menu custom-action-dropdown"
                                                        aria-labelledby="dropdownMenuButton">
                                                        <a href="#"
                                                            class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"

                                                            @click="editIdMethod('paintsurface', item.id)">Edit</a>
                                                        <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                                            class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                                            href="#"
                                                            @click="deleteRequest(item.id, 'Paint Surface', 'paint-surface')">Delete
                                                        </a>

                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div v-if="!loading && paint_surfaces.length <= 0">
                                        <EmptySearch></EmptySearch>
                                    </div>
                                    <Pagination v-if="paint_surfaces.length > 0 && table_paint_surfaces.last_page > 1"
                                        @changetable="getPaintSurfaces" :table_data="table_paint_surfaces"></Pagination>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--            Add Record Models-->

            <div class="modal fade custom_base_model custom_base_model_small" data-bs-backdrop="static"
                data-bs-keyboard="false" id="PaintColorAdd" tabindex="-1" aria-labelledby="apaintColorAddAddLabel"
                aria-hidden="true">
                <AddColor @closeEvent="forceRerender"></AddColor>
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" data-bs-backdrop="static"
                data-bs-keyboard="false" id="PaintFinishingAdd" tabindex="-1" aria-labelledby="apaintFinishingAddAddLabel"
                aria-hidden="true">
                <AddFinishing @closeEvent="forceRerender"></AddFinishing>
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" data-bs-backdrop="static"
                data-bs-keyboard="false" id="PaintGradeAdd" tabindex="-1" aria-labelledby="apaintFinishingAddAddLabel"
                aria-hidden="true">
                <AddGrade @closeEvent="forceRerender"></AddGrade>
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" data-bs-backdrop="static"
                data-bs-keyboard="false" id="PaintManufacturerAdd" tabindex="-1"
                aria-labelledby="apaintFinishingAddAddLabel" aria-hidden="true">
                <AddManufacturer @closeEvent="forceRerender"></AddManufacturer>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" data-bs-backdrop="static"
                data-bs-keyboard="false" id="PaintMethodAdd" tabindex="-1" aria-labelledby="apaintFinishingAddAddLabel"
                aria-hidden="true">
                <AddMethod @closeEvent="forceRerender"></AddMethod>
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" data-bs-backdrop="static"
                data-bs-keyboard="false" id="PaintSurfaceAdd" tabindex="-1" aria-labelledby="apaintFinishingAddAddLabel"
                aria-hidden="true">
                <AddSurface :paintMethods="paint_methods" @closeEvent="forceRerender"></AddSurface>
            </div>

            <!--            Delete Model-->

            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
            </div>


            <!--            Edit Models -->

            <template v-if="showModals">
            <div class="modal fade custom_base_model custom_base_model_small" id="PaintColorEdit" tabindex="-1"
                aria-labelledby="serviceTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
                <EditColor @closeEvent="forceRerender" :paintColorId="paintColorId"></EditColor>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" id="PaintFinishingEdit" tabindex="-1"
                aria-labelledby="serviceTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
                <EditFinishing @closeEvent="forceRerender" :paintFinishingId="paintFinishingId"></EditFinishing>
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" id="PaintGradeEdit" tabindex="-1"
                aria-labelledby="serviceTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
                <EditGrade  @closeEvent="forceRerender" :paintGradeId="paintGradeId"></EditGrade>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" id="PaintManufacturerEdit" tabindex="-1"
                aria-labelledby="serviceTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
                <EditManufacturer @closeEvent="forceRerender" :paintManufacturerId="paintManufacturerId"></EditManufacturer>
            </div>


            <div class="modal fade custom_base_model custom_base_model_small" id="PaintMethodEdit" tabindex="-1"
                aria-labelledby="serviceTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
                <EditMethod @closeEvent="forceRerender" :paintMethodId="paintMethodId"></EditMethod>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" id="PaintSurfaceEdit" tabindex="-1"
                aria-labelledby="serviceTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
                <EditSurface v-if="paintsurfaceShow" :paintMethods="paint_methods" @closeEvent="forceRerender" :paintSurfaceId="paintSurfaceId">
                </EditSurface>
            </div>
        </template>




        </div>
    </div>
</template>


<script>

import AddColor from "../paint_values/paint-color/Add";
import AddFinishing from "../paint_values/paint-finishing/Add";
import AddGrade from "../paint_values/paint-grade/Add";
import AddManufacturer from "../paint_values/paint-manufacturer/Add";
import AddMethod from "../paint_values/paint-method/Add";
import AddSurface from "../paint_values/paint-surface/Add";


import EditColor from "../paint_values/paint-color/Edit";
import EditFinishing from "../paint_values/paint-finishing/Edit";
import EditGrade from "../paint_values/paint-grade/Edit";
import EditManufacturer from "../paint_values/paint-manufacturer/Edit";
import EditMethod from "../paint_values/paint-method/Edit";
import EditSurface from "../paint_values/paint-surface/Edit";

import DeleteModel from "../deleteModel";
import Loader from "../LoaderSpinner";



import axios from "axios";
import Pagination from "../Pagination";
import SingleFieldSearch from "../SingleFieldSearch";
import EmptySearch from "../EmptySearch";
import Navigation from "../../components/Navigation";
export default {
    components: {
        'AddColor': AddColor,
        'AddFinishing': AddFinishing,
        'AddGrade': AddGrade,
        'AddManufacturer': AddManufacturer,
        'AddMethod': AddMethod,
        'AddSurface': AddSurface,

        'EditColor': EditColor,
        'EditFinishing': EditFinishing,
        'EditGrade': EditGrade,
        'EditManufacturer': EditManufacturer,
        'EditMethod': EditMethod,
        'EditSurface': EditSurface,

        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'EmptySearch': EmptySearch,
        'Navigation': Navigation,
    },
    data() {
        return {
            paintsurfaceShow:true,
            showModals: true,
            successmsg: "",
            deletemsg: "",
            has_error: false,
            loading: true,
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            filterLoader: false,

            paint_colors: [],
            paint_finishings: [],
            paint_grades: [],
            paint_manufacturers: [],
            paint_methods: [],
            paint_surfaces: [],

            paintColorId: "",
            paintFinishingId: "",
            paintGradeId: "",
            paintManufacturerId: "",
            paintMethodId: "",
            paintSurfaceId: "",

            table_paint_colors: {
                current_page: 1,
                per_page: 25,
                last_page: 0,
                search: "",
            },

            table_paint_finishings: {
                current_page: 1,
                per_page: 25,
                last_page: 0,
                search: "",
            },

            table_paint_grades: {
                current_page: 1,
                per_page: 25,
                last_page: 0,
                search: "",
            },

            table_paint_manufacturers: {
                current_page: 1,
                per_page: 25,
                last_page: 0,
                search: "",
            },

            table_paint_methods: {
                current_page: 1,
                per_page: 25,
                last_page: 0,
                search: "",
            },

            table_paint_surfaces: {
                current_page: 1,
                per_page: 25,
                last_page: 0,
                search: "",
            },
            sortOrder: "desc",
            sortBy: "id",
            activeTabId: "#home-tab",
            activeTabTarget: "#paint_colors",
        }
    },
    mounted() {
        this.refreshRecord(this.activeTabTarget);
    },
    methods: {

         refreshRecord(targetId) {
            this.loading = true;
            if (targetId) {
                switch(targetId){
                    case '#paint_colors':
                        this.getPaintColors();
                    break;
                    case '#paint_finishings':
                        this.getPaintFinishings();
                    break;
                    case '#paint_grades':
                        this.getPaintGrades();
                    break;

                    case '#paint_manufacturers':
                        this.getPaintManufacturers();
                    break;

                    case '#paint_methods':
                        this.getPaintMethods();
                    break;

                    case '#paint_surfaces':
                        this.getPaintSurfaces();
                        this.getPaintMethods();
                    break;
                    default:
                        this.getPaintColors();
                        this.getPaintFinishings();
                        this.getPaintGrades();
                        this.getPaintManufacturers();
                        this.getPaintMethods();
                        this.getPaintSurfaces();
                }
            }


            // setTimeout(() => {
            //     this.setActiveTab()
            // }, 2000);
        },
        sorting(sortby) {
           this.sortOrder = this.sortBy != sortby || this.sortOrder === "asc" ? "desc" : "asc";
            this.sortBy = sortby;
            this.refreshRecord(this.activeTabTarget );

        },
        activeTab(tabId, targetId) {
            if (tabId != this.activeTabId) {
                this.sortOrder='asc';
                this.sortBy='name';
                this.refreshRecord(targetId);
                $(this.activeTabId).prop('aria-selected', false);
                $(this.activeTabId).removeClass("active");
                $(this.activeTabTarget).removeClass("show active");

                this.activeTabId = tabId;
                this.activeTabTarget = targetId;
            }

        },
        setActiveTab() {
            $(this.activeTabId).addClass('active')
            $(this.activeTabId).attr('aria-selected', true);
            $(this.activeTabTarget).addClass("show active");
        },

        getPaintColorsSearch() {
            this.table_paint_colors.current_page = 1;
            this.getPaintColors();
        },
        getPaintColors() {
            this.filterLoader = true;
            axios.get('/api/paint-color?page=' + this.table_paint_colors.current_page + '&size=' + this.table_paint_colors.per_page + '&search=' + this.table_paint_colors.search + '&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy)
                .then((response) => {
                    this.loading = false;
                    this.table_paint_colors.last_page = response.data.paint_colors.last_page;
                    this.paint_colors = response.data.paint_colors.data;
                    this.filterLoader = false;
                    this.setActiveTab()
                });
        },

        getPaintFinishingsSearch() {
            this.loading = true;
            this.table_paint_finishings.current_page = 1;
            this.getPaintFinishings();

        },

        getPaintFinishings() {
            this.filterLoader = true;
            axios.get('/api/paint-finishing?page=' + this.table_paint_finishings.current_page + '&size=' + this.table_paint_finishings.per_page + '&search=' + this.table_paint_finishings.search + '&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy)
                .then((response) => {
                    this.loading = false;
                    this.table_paint_finishings.last_page = response.data.paint_finishings.last_page;
                    this.paint_finishings = response.data.paint_finishings.data;
                    this.setActiveTab()
                    this.filterLoader = false;
                });
        },

        getPaintGradesSearch() {
            this.loading = true;
            this.table_paint_grades.current_page = 1;
            this.getPaintGrades();
        },

        getPaintGrades() {
            this.filterLoader = true;
            axios.get('/api/paint-grade?page=' + this.table_paint_grades.current_page + '&size=' + this.table_paint_grades.per_page + '&search=' + this.table_paint_grades.search + '&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy)
                .then((response) => {
                    this.table_paint_grades.last_page = response.data.paint_grades.last_page;
                    this.paint_grades = response.data.paint_grades.data;
                    this.loading = false;
                    this.filterLoader = false;
                    this.setActiveTab()
                });
        },


        getPaintManufacturersSearch() {
            this.loading = true;
            this.table_paint_manufacturers.current_page = 1;
            this.getPaintManufacturers();
        },
        getPaintManufacturers() {
            this.filterLoader = true;
            axios.get('/api/paint-manufacturer?page=' + this.table_paint_manufacturers.current_page + '&size=' + this.table_paint_manufacturers.per_page + '&search=' + this.table_paint_manufacturers.search + '&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy)
                .then((response) => {
                    this.table_paint_manufacturers.last_page = response.data.paint_manufacturers.last_page;
                    this.paint_manufacturers = response.data.paint_manufacturers.data;
                    this.loading = false;
                    this.filterLoader = false;
                    this.setActiveTab()
                });
        },

        getPaintMethodsSearch() {
            this.loading = true;
            this.table_paint_methods.current_page = 1;
            this.getPaintMethods();
        },

        getPaintMethods() {
            this.filterLoader = true;
            axios.get('/api/paint-method?page=' + this.table_paint_methods.current_page + '&size=' + this.table_paint_methods.per_page + '&search=' + this.table_paint_methods.search + '&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy)
                .then((response) => {
                    this.table_paint_methods.last_page = response.data.paint_methods.last_page;
                    this.paint_methods = response.data.paint_methods.data;
                    console.log("----");
                    console.log(this.paint_methods);
                    this.loading = false;
                    this.filterLoader = false;
                    this.setActiveTab()
                });
        },

        getPaintSurfacesSearch() {
            this.loading = true;
            this.table_paint_surfaces.current_page = 1;
            this.getPaintSurfaces();
        },

        getPaintSurfaces() {
            this.filterLoader = true;
            axios.get('/api/paint-surface?page=' + this.table_paint_surfaces.current_page + '&size=' + this.table_paint_surfaces.per_page + '&search=' + this.table_paint_surfaces.search + '&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy)
                .then((response) => {
                    this.table_paint_surfaces.last_page = response.data.paint_surfaces.last_page;
                    this.paint_surfaces = response.data.paint_surfaces.data;
                    this.loading = false;
                    this.filterLoader = false;
                    this.setActiveTab()
                });
        },

        forceRerender(msg) {
                this.paintSurfaceId = "",
                this.paintColorId = "",
                this.paintFinishingId = "",
                this.paintGradeId = "",
                this.paintManufacturerId = "",
                this.paintMethodId = "",


                this.successmsg = "";

            // console.log(msg);
            if(msg!="no-refresh")   { this.successmsg = msg; }
            this.refreshRecord(this.activeTabTarget);


            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
        },

        editIdMethod(type, id) {
            if (type == "paintcolor") {
                this.paintColorId = id;
            }
            if (type == "paintfinishing") {
                this.paintFinishingId = id;
            }
            if (type == "paintgrade") {
                // console.log('bbbb')

                this.paintGradeId = id;

                // console.log(this.paintGradeId);

            }
            if (type == "paintmanufacturer") {
                this.paintManufacturerId = id;
            }
            if (type == "paintmethod") {
                this.paintMethodId = id;
            }
            if (type == "paintsurface") {
                this.paintsurfaceShow = false;
                this.paintsurfaceShow=true;
                setTimeout(()=>{
                    this.paintSurfaceId = id;
                    $("#PaintSurfaceEdit").modal('show');
                },300)
            }

        },

        deleteRequest(id, title, api) {
            this.deleteId = id;
            this.deleteTitle = title;
            this.deleteApi = api;
        },
        deleteRecord(val) {
            if (val == 0) {
                this.deleteId = "";
                this.deleteTitle = "";
                this.deleteApi = "";
                return
            }
            axios.delete('/api/' + this.deleteApi + '/' + this.deleteId)
                .then((response) => {
                    this.deletemsg = response.data.msg;
                    if (response.data.error) {
                        this.deletemsg = response.data.error
                    }
                    this.refreshRecord( this.activeTabId, this.activeTabTarget );
                });
            setTimeout(() => {
                this.deletemsg = "";
            }, 3000);
        },

    }
}


</script>
