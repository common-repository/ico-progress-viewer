<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.icoconsulting.asia
 * @since      1.0.0
 *
 * @package    Ico_Progress_Viewer
 * @subpackage Ico_Progress_Viewer/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <form method="post" name="ico_progress_viewer" action="options.php">

        <?php
            $options = get_option($this->plugin_name);
            settings_fields($this->plugin_name);
            //do_settings_sections($this->plugin_name);
        ?>

        <h2>General</h2>

        <table class="form-table">

            <!-- short code -->
	        <tr>
                <th>
                    <label for="<?php echo $this->plugin_name; ?>-shortcode">Shortcode:</label>
                </th>
                <td>
                    <input type="text"
                        required
                        placeholder="<?php echo $this->plugin_name ?>"
                        id="<?php echo $this->plugin_name; ?>-shortcode"
                        name="<?php echo $this->plugin_name; ?>[shortcode]"
                        value="<?php echo (empty($options['shortcode'])) ? $this->plugin_name : $options['shortcode'] ?>"
                        class="regular-text" />
                    <p class="description" id="tagline-description">The shortcode to use in any post or page to render this plugin. Defaults to 'ico-progress-viewer'</p>
                </td>
            </tr>

            <!-- date format string (compatible with moment library) -->
	        <tr>
                <th>
                    <label for="<?php echo $this->plugin_name; ?>-date-format">Date Format:</label>
                </th>
                <td>
                    <input type="text"
                        required
                        placeholder="dddd, MMMM Do YYYY, h:mm:ss a"
                        id="<?php echo $this->plugin_name; ?>-date-format"
                        name="<?php echo $this->plugin_name; ?>[date_format]"
                        value="<?php echo (empty($options['date_format'])) ? 'lll' : $options['date_format'] ?>"
                        class="regular-text" />
                    <p class="description" id="tagline-description">A moment-compatible date format string for the formatting of ICO start and end dates. See the <a target="_blank" href="https://momentjs.com/docs/#/displaying/format/">Moment documentation</a> for more info. Defaults to 'lll'</p>
                </td>
            </tr>
        </table>

        <h2>Smart Contract Basic Details</h2>

        <table class="form-table">
            <!-- smart contract address -->
	        <tr>
                <th>
                    <label for="<?php echo $this->plugin_name; ?>-smart_contract_address">Smart Contract Address:</label>
                </th>
                <td>
                    <input type="text"
                        required
                        placeholder="e.g. 0xaBd898bc036a3AaEef442F9C74bCaA458Fa0F62e"
                        id="<?php echo $this->plugin_name; ?>-smart_contract_address"
                        name="<?php echo $this->plugin_name; ?>[smart_contract_address]"
                        value="<?php if(!empty($options['smart_contract_address']))  echo $options['smart_contract_address']; ?>"
                        class="regular-text" />
                    <p class="description" id="tagline-description">The public address of the smart contract where contributions are sent</p>
                </td>
            </tr>

            <!-- gateway url -->
	        <tr>
                <th>
                    <label for="<?php echo $this->plugin_name; ?>-gateway_url">Gateway URL:</label>
                </th>
                <td>
                    <input type="url"
                        required
                        placeholder="e.g. https://mainnet.infura.io"
                        id="<?php echo $this->plugin_name; ?>-gateway_url"
                        name="<?php echo $this->plugin_name; ?>[gateway_url]"
                        value="<?php if(!empty($options['gateway_url'])) echo $options['gateway_url']; ?>"
                        class="regular-text" />
                    <p class="description" id="tagline-description">The public URL of the gateway or proxy to the Ethereum network. Examples: infura.io, api.etherscan.io, api.myetherapi.com</p>
                </td>
            </tr>

            <!-- abi -->
            <tr>
                <th>
                    <label for="<?php echo $this->plugin_name; ?>-abi">ABI</label>
                </th>
                <td>
                    <textarea id="<?php echo $this->plugin_name; ?>-abi" name="<?php echo $this->plugin_name; ?>[abi]" rows="10" class="large-text"><?php if(!empty($options['abi'])) echo $options['abi']; ?></textarea>
                    <p class="description" id="tagline-description">The JavaScript interface for the smart contract</p>
                </td>
            </tr>
        </table>


        <details class="primer">
            <summary title="Advanced customisation">••• Smart Contract advanced customisation options</summary>
            <section>
                <h2>Advanced Smart Contract Method Mapping</h2>
                <p>These advanced settings allow you to customise the Smart Contract method names which are called to retrieve data for the widget to render. If your Smart Contract is based on the OpenZeppelin library then it's unlikely you will need to change any of the defaults here</p>

                <table class="form-table">
                    <!-- total raised = weiRaised(); -->
                    <tr>
                        <th>
                            <label for="<?php echo $this->plugin_name; ?>-total-raised">Total raised contract method:</label>
                        </th>
                        <td>
                            <input type="text"
                                placeholder="weiRaised()"
                                id="<?php echo $this->plugin_name; ?>-total-raised"
                                name="<?php echo $this->plugin_name; ?>[total_raised]"
                                value="<?php echo (empty($options['total_raised'])) ? 'weiRaised()' : $options['total_raised'] ?>"
                                class="regular-text" />
                            <p class="description" id="tagline-description">The smart contract method name to retrieve the total amount raised in the ICO</p>
                        </td>
                    </tr>

                    <!-- start time = startTime() -->
                    <tr>
                        <th>
                            <label for="<?php echo $this->plugin_name; ?>-start-time">Start time contract method:</label>
                        </th>
                        <td>
                            <input type="text"
                                placeholder="startTime()"
                                id="<?php echo $this->plugin_name; ?>-start-time"
                                name="<?php echo $this->plugin_name; ?>[start_time]"
                                value="<?php echo (empty($options['start_time'])) ? 'startTime()' : $options['start_time'] ?>"
                                class="regular-text" />
                            <p class="description" id="tagline-description">The smart contract method name to retrieve the ICO start datetime</p>
                        </td>
                    </tr>

                    <!-- end time = endTime() -->
                    <tr>
                        <th>
                            <label for="<?php echo $this->plugin_name; ?>-end-time">End time contract method:</label>
                        </th>
                        <td>
                            <input type="text"
                                placeholder="endTime()"
                                id="<?php echo $this->plugin_name; ?>-end-time"
                                name="<?php echo $this->plugin_name; ?>[end_time]"
                                value="<?php echo (empty($options['end_time'])) ? 'endTime()' : $options['end_time'] ?>"
                                class="regular-text" />
                            <p class="description" id="tagline-description">The smart contract method name to retrieve the ICO end datetime</p>
                        </td>
                    </tr>

                    <!-- min cap = minCap() -->
                    <tr>
                        <th>
                            <label for="<?php echo $this->plugin_name; ?>-min-cap">Min cap contract method:</label>
                        </th>
                        <td>
                            <input type="text"
                                placeholder="minCap()"
                                id="<?php echo $this->plugin_name; ?>-min-cap"
                                name="<?php echo $this->plugin_name; ?>[min_cap]"
                                value="<?php echo (empty($options['min_cap'])) ? 'minCap()' : $options['min_cap'] ?>"
                                class="regular-text" />
                            <p class="description" id="tagline-description">The smart contract method name to retrieve the ICO minimum cap (goal amount)</p>
                        </td>
                    </tr>

                    <!-- goal = cap() -->
                    <tr>
                        <th>
                            <label for="<?php echo $this->plugin_name; ?>-max-cap">Max cap contract method:</label>
                        </th>
                        <td>
                            <input type="text"
                                placeholder="cap()"
                                id="<?php echo $this->plugin_name; ?>-max-cap"
                                name="<?php echo $this->plugin_name; ?>[max_cap]"
                                value="<?php echo (empty($options['max_cap'])) ? 'cap()' : $options['max_cap'] ?>"
                                class="regular-text" />
                            <p class="description" id="tagline-description">The smart contract method name to retrieve the ICO maximum cap amount<p>
                        </td>
                    </tr>

                </table>

            </section>
        </details>









        <?php submit_button('Save all changes', 'primary','submit', TRUE); ?>

    </form>

</div>
