<?php
$tru_sobeys_instance = new \TRU\SOBYES_CAREERS\Classes\TRU_SOBYES_CAREERS_FRONTEND();
$per_page = defined('PER_PAGE') ? PER_PAGE : 30;
$Api_Data = $tru_sobeys_instance->Sobeys_API_Json_Data();
$All_Data = array_slice( $Api_Data, 0, $per_page);
$show_count_label = $tru_sobeys_instance->Api_Data_Result_Count(array('total' => count($Api_Data), 'current' => 1, 'limit' => $per_page));
$total_pages = ( count($Api_Data) > $per_page ) ? ceil( count($Api_Data) / $per_page ) : '1';
$pagination_label = $tru_sobeys_instance->Sobeys_Filter_Pagination(array('total' => $total_pages, 'current' => 1 ));

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

                <div class="row">
                    <div class="col-4">

                        <div class="filter_main">
                            <h2>Filter</h2>
                            <div class="filter">
                                <input type="text" id="inputField" placeholder="Value">

                                <select id="languageDropdown">
                                    <option value="" disabled selected>Select Language</option>
                                    <?php foreach ($jobs as $job): ?>
                                    <option value="<?php echo htmlspecialchars($job['ID']); ?>">
                                        <?php echo htmlspecialchars(($job['ID'])); ?>
                                    </option>
                                    <?php endforeach; ?>


                                </select>

                                <select id="bannerDropdown">
                                    <option value="" disabled selected>Select Banner</option>
                                    <option value="banner1">Banner 1</option>
                                    <option value="banner2">Banner 2</option>
                                    <option value="banner3">Banner 3</option>
                                </select>

                                <!-- Business Unit Input -->
                                <input type="text" id="businessUnit" placeholder="Enter Business Unit">

                                <!-- Experience Level Dropdown -->
                                <select id="experienceLevel">
                                    <option value="" disabled selected>Experience Level</option>
                                    <option value="junior">Junior</option>
                                    <option value="mid">Mid</option>
                                    <option value="senior">Senior</option>
                                    <option value="expert">Expert</option>
                                </select>

                                <!-- Date Posted Dropdown -->
                                <select id="datePosted">
                                    <option value="" disabled selected>Date Posted</option>
                                    <?php foreach ($jobs as $job): ?>
                                    <option value="english"
                                        <?php echo ($job['defaultLanguage'] == 'english') ? 'selected' : ''; ?>>English
                                    </option>

                                    <?php endforeach; ?>
                                </select>

                                <!-- Job Type Dropdown -->
                                <select id="jobType">
                                    <option value="" disabled selected>Job Type</option>
                                    <option value="fullTime">Full-Time</option>
                                    <option value="partTime">Part-Time</option>
                                    <option value="contract">Contract</option>
                                    <option value="internship">Internship</option>
                                </select>

                                <!-- Location Input -->
                                <input type="text" id="location" placeholder="Enter Location">

                                <!-- Apply Button -->
                                <div class="applyButton">
                                    <button id="applyFilters">Apply</button>

                                </div>
                            </div>`

                        </div>
                    </div>

                    <div class="col-8">
                        <div class="table">
                            <!-- Loader Container -->
                            <div id="loader" class="loader-container">
                                <div class="spinner"></div>
                            </div>
                            <div id="carrers-results">

                                <div class="show_count_label"><?php echo $show_count_label; ?></div>
                                <div class="pagination_label">
                                    <ul>
                                        <?php echo $pagination_label; ?>
                                    </ul>
                                </div>
                            
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Category</th>
                                            <th>Banner</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="carrers-data">
                                        <?php foreach ($All_Data as $value){ 
                                            echo $tru_sobeys_instance->get_Job_API_Results_View( $value );
                                         } ?>
                                    </tbody>
                                </table>
                                <div class="show_count_label"><?php echo $show_count_label; ?></div>
                                <div class="pagination_label">
                                    <ul>
                                        <?php echo $pagination_label; ?>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </section>
