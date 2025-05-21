<?php
use TRU\SOBYES_CAREERS\Classes\TRU_SOBYES_CAREERS_FRONTEND;
$per_page = defined('PER_PAGE') ? PER_PAGE : 30;
$Api_Data = TRU_SOBYES_CAREERS_FRONTEND::Sobeys_API_Json_Data();
$All_Data = array_slice( $Api_Data, 0, $per_page);
$show_count_label = TRU_SOBYES_CAREERS_FRONTEND::Api_Data_Result_Count(count($Api_Data), 1 );
$total_pages = ( count($Api_Data) > $per_page ) ? ceil( count($Api_Data) / $per_page ) : '1';
$pagination_label = TRU_SOBYES_CAREERS_FRONTEND::Sobeys_Filter_Pagination(array('total' => $total_pages, 'current' => 1 ));

$result .= '<ul class="jobs_pagination">'.$pagination_label.'</ul>';
?>


    <section>
        <div class="container">
            <main> 
                <div class="modal" id="jobModal">
                    <div class="modal-content">
                        <button class="close-modal">&times;</button>
                        <h2>Job Details</h2>
                        <div id="jobDetails">
                            <p>Loading...</p> 
                        </div>
                    </div>
                </div>
                <section class="career_sec">
                    <div class="row">
                    
                        <div class="col-3 filter">
                            <div class="career_sidebar">
                                <h2>Filter</h2>
                                
                                <div class="career_sidebar_data">
                                    <input type="text" id="inputField" placeholder="Title" class="is-tabbing">
                                    <div class="custom-dropdown" id="categoryDropdown">
                                        <div class="dropdown-selected">Select Language</div>
                                             <ul class="dropdown-options">
                                                    <li data-value="Store Careers" value="fr_CA">English</li>
                                                    <li data-value="Pharmacy Careers" value="en_GB">French</li>
                                            </ul>
                                                <input type="hidden" id="categorySearch" value="">
                                    </div>
                                    <!-- <select id="languageDropdown" class="is-tabbing">
                                        <option value="">Select Language</option>
                                        <option value="en_GB">English</option>
                                        <option value="fr_CA">French</option>
                                    </select> -->

                                    <select id="bannerDropdown" class="is-tabbing">
                                        <option value="">Select Banner</option>
                                        <option value="banner1">Sobeys</option>
                                        <option value="banner2">Safeway</option>
                                    </select>

                                    <!-- Business Unit Input -->
                                    <input type="text" id="businessUnit" placeholder="Enter Business Unit" class="is-tabbing">

                                    <!-- Experience Level Dropdown -->
                                    <select id="experienceLevel" class="is-tabbing">
                                        <option value="">Experience Level</option>
                                        <option value="junior">Junior</option>
                                        <option value="mid">Mid</option>
                                        <option value="senior">Senior</option>
                                        <option value="expert">Expert</option>
                                    </select>

                                    <!-- Date Posted Dropdown -->
                                    <select id="datePosted" class="is-tabbing">
                                        <option value="">Date Posted</option>
                                    </select>

                                    <!-- Job Type Dropdown -->
                                    <select id="jobType" class="is-tabbing">
                                        <option value="" disabled="" selected="">Job Type</option>
                                        <option value="fullTime">Full-Time</option>
                                        <option value="partTime">Part-Time</option>
                                        <option value="contract">Contract</option>
                                        <option value="internship">Internship</option>
                                    </select>

                                    <!-- Location Input -->
                                    <input type="text" id="location" placeholder="Enter Location" class="is-tabbing">
                                    <label><strong>Distance:</strong> <span class="distance_current">50</span> Kilometers</label>
                                    <input type="range" id="distance_km" name="volume" min="1" max="100" value="50" class="is-tabbing">

                                    <!-- Apply Button -->
                                    <div class="applyButton">
                                        <button id="applyFilters" class="is-tabbing apply-filter-form">Apply</button>
                                        <button class="reset_button is-tabbing underline_text">Reset</button>
                                    </div>
                                </div>`
                            </div>
                        </div>

                        <div class="col-9 mb_career_table_section">
                            <div class="career_table_section">
                                <!-- Loader Container -->
                                <div id="loader" class="loader-container">
                                    <div class="spinner"></div>
                                </div>
                                <div id="carrers-results">

                                    
                                    <div class="show_count_label">

                                        <div class="result-counter" role="status">
                                            <span class="total-item-count" tabindex="0"><?php echo $show_count_label; ?></span>
                                        </div>

                                        <div class="career_table_pagination">
                                            <ul class="jobs_pagination">
                                                <?php echo $pagination_label; ?>
                                            </ul>
                                        </div>

                                        
                                    </div>
                          
                                
                                    <table>
                                    <thead class="career_table_heading">
                                        <tr>
                                            <th>Title</th>
                                            <th>Location</th>
                                            <th>Category</th>
                                            <th>Banner</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="carrers-data" class="career_table_body">
                                            <?php foreach ($All_Data as $value){ 
                                                $location_array = [$value['City'], $value['State'], $value['Country'], $value['PostalCode']]; ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($value['Title']); ?></td>
                                                <td><?php echo htmlspecialchars(implode(', ', $location_array)); ?></td>
                                                <td><?php echo htmlspecialchars($value['Category'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($value['Company'] ?? 'N/A'); ?></td>
                                                <td><a href="#" data-id="<?php echo $value['ID'] ?? ''; ?>" class="job_view_detail">View Details</a></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <div class="show_count_label">

                                        <div class="result-counter" role="status">
                                            <span class="total-item-count" tabindex="0"><?php echo $show_count_label; ?></span>
                                        </div>

                                        <div class="career_table_pagination">
                                            <ul class="jobs_pagination">
                                                <?php echo $pagination_label; ?>
                                            </ul>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </section>


                

            </main>
        </div>
    </section>
    <section class="single_modal_content" style="display:none;">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="job_modal_body"></div>
    </section>


