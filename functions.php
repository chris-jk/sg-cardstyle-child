<?php
// works
function displaySVGFiles($fileName, $themeColor = '#0CE4A3')
{
  // Get the current directory
  $currentDir = __DIR__;

  // Specify the folder name
  $folderName = 'svgs';

  // Build the folder path
  $folderPath = $currentDir . '/' . $folderName;

  // Construct the full file path
  $filePath = $folderPath . '/' . $fileName . '.svg';

  // Check if the file exists
  if (file_exists($filePath)) {
    // Read the SVG file content
    $svgContent = file_get_contents($filePath);

    // Add the default theme color to the SVG content
    $svgContentWithColor = str_replace('<svg', '<svg fill="' . $themeColor . '"', $svgContent);

    // Return the modified SVG content
    return $svgContentWithColor;

  } else {
    return "";
  }
}


// select from tags searchable
function enqueue_select2_jquery()
{
  wp_register_style('select2css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css');
  wp_register_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), '4.0.13', true);
  wp_enqueue_style('select2css');
  wp_enqueue_script('select2');

  // Add inline script
  $inline_script = 'jQuery(document).ready(function($) {
        $("#hcf_grow_dif").select2();
    });';
  wp_add_inline_script('select2', $inline_script);
}
add_action('wp_enqueue_scripts', 'enqueue_select2_jquery');


// add custom child theme support
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');
function my_theme_enqueue_styles()
{
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
  wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'), wp_get_theme()->get('Version'));
}

// add custom feilds to posts content function
function prefix_add_content($content)
{
  $custom_fields_aka_ratings_type = array(
    'hcf_aka',
    'hcf_star_ratings',
    'hcf_ratings_amount',
    'hcf_strain_type'
  );
  $custom_fields_dominate_terp = array(
    'hcf_dominate_terp'
  );
  $custom_fields_other_terp = array(
    'hcf_other_terp_1',
    'hcf_other_terp_2'
  );
  $custom_fields_thc_cbg_cbd = array(
    'hcf_THC',
    'hcf_CBG',
    'hcf_CBD',
    'hcf_CBN'
  );
  $custom_fields_flav = array(
    'hcf_flav_1',
    'hcf_flav_2',
    'hcf_flav_3'
  );
  $custom_fields_feel = array(
    'hcf_feel_1',
    'hcf_feel_2',
    'hcf_feel_3'
  );
  $custom_fields_help = array(
    'hcf_help_1',
    'hcf_help_2',
    'hcf_help_3'
  );
  $custom_fields_neg = array(
    'hcf_neg_1',
    'hcf_neg_2',
    'hcf_neg_3'
  );
  $custom_fields_parent_child = array(
    'hcf_parent_1',
    'hcf_parent_2',
    'hcf_child_1',
    'hcf_child_2'
  );
  $custom_fields_grow = array(
    'hcf_grow_dif',
    'hcf_grow_avg_hight',
    'hcf_grow_avg_yeild',
    'hcf_grow_time'
  );


  // check strain_type to add title to tables
  if (esc_attr(get_post_meta(get_the_ID(), 'hcf_strain_type', true))) {
    $title = get_the_title();
    $info = '<h3>' . $title . ' Info</h3>';
  }
  if (!empty(get_post_meta(get_the_ID(), 'hcf_seed_link', true))) {
    $seed_link = "<a href=" . get_post_meta(get_the_ID(), 'hcf_seed_link', true) . ">Find " . $title . " Seed Here</a>";
  }
  if (!empty(get_post_meta(get_the_ID(), 'hcf_dominate_terp', true))) {
    $terp_profile = '<h3>' . $title . ' Terpene Profile</h3>';
  }

  if (!empty(get_post_meta(get_the_ID(), 'hcf_flav_1', true))) {
    $flavs = '<h3>' . $title . ' Flavors</h3>';
  }
  if (!empty(get_post_meta(get_the_ID(), 'hcf_feel_1', true))) {
    $feels = '<h3>' . $title . ' Feelings</h3>';
  }
  if (!empty(get_post_meta(get_the_ID(), 'hcf_help_1', true))) {
    $may_help = '<h3>' . $title . ' May help with</h3>';
  }
  if (!empty(get_post_meta(get_the_ID(), 'hcf_neg_1', true))) {
    $neg = '<h3>' . $title . ' Possible Negatives</h3>';
  }
  if (!empty(get_post_meta(get_the_ID(), 'hcf_parent_1', true) || !empty(get_post_meta(get_the_ID(), 'hcf_child_1', true)))) {
    $genetics = '<h3>' . $title . ' Genetics</h3>';
  }
  if (!empty(get_post_meta(get_the_ID(), 'hcf_grow_dif', true))) {
    $grow = '<h3>' . $title . ' Grow Info</h3>';
  }

  //fix_generate_table 2 columns name:value
  $table_aka = prefix_generate_table($custom_fields_aka_ratings_type);
  $table_thc_cbg_cbd = prefix_generate_table($custom_fields_thc_cbg_cbd);
  $table_dominate_terp = prefix_generate_table($custom_fields_dominate_terp);
  $table_other_terp = prefix_generate_table($custom_fields_other_terp);

  // prefix_generate_table_3 3 columns
  $new_content .= $content;
  $table_flav = prefix_generate_table_3($custom_fields_flav);
  $table_feel = prefix_generate_table_3($custom_fields_feel);
  $table_help = prefix_generate_table_3($custom_fields_help);
  $table_neg = prefix_generate_table_3($custom_fields_neg);
  $table_parent_child = prefix_generate_table($custom_fields_parent_child);
  $table_grow = prefix_generate_table($custom_fields_grow);
  $hcf_grow_notes = get_post_meta(get_the_ID(), 'hcf_grow_notes', true);


  // Add more tables as needed...
  $new_content = $table_dominate_terp . $table_aka . $table_thc_cbg_cbd . $terp_profile . $table_dominate_terp .
    $table_other_terp . $info . $new_content . $flavs . $table_flav . $feels . $table_feel . $may_help . $table_help . $neg . $table_neg . $seed_link . $genetics . $table_parent_child . $grow . $table_grow . $hcf_grow_notes;

  return $new_content;
}
add_filter('the_content', 'prefix_add_content');


function prefix_generate_table($fields)
{
  $table = '<table><tbody>';
  $hasValue = false;

  foreach ($fields as $field) {
    $field_value = get_post_meta(get_the_ID(), $field, true);

    if ($field_value) {
      $hasValue = true;

      // Remove 'hcf_' from the field name and capitalize each word
      $field_name = str_replace('hcf_', '', $field);
      $field_name = str_replace('_', ' ', $field_name);
      $field_name = ucwords($field_name);

      // Add different SVGs depending on the field name
      switch ($field_name) {
        case 'THC':
          $svg = '<svg width="20" height="20">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.523 9.648L9.648 3.773 3.773 9.648l5.875 5.875 5.875-5.875zM9.648.944L.945 9.648l8.703 8.704 8.704-8.704L9.648.944z" fill="#38C7AE"></path>
                    </svg> ' . ' % ';
          break;
        case 'CBD':
          $svg = '<svg width="20" height="20">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.73 4.044a6.039 6.039 0 108.54 8.54 6.039 6.039 0 00-8.54-8.54zm9.927-1.387A8 8 0 102.343 13.97 8 8 0 0013.657 2.657z" fill="#38C7AE"></path>
                    </svg> ' . ' % ';
          break;
        case 'CBG':
          $svg = '<svg width="20" height="20">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.907 15L19 .75H.813L9.907 15zm0-3.718L15.35 2.75H4.462l5.445 8.532z" fill="#38C7AE"></path>
                    </svg> ' . ' % ';
          break;
        case 'CBN':
          $svg = '<svg width="20" height="20">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.907 15L19 .75H.813L9.907 15zm0-3.718L15.35 2.75H4.462l5.445 8.532z" fill="#38C7AE"></path>
                    </svg> ' . ' % ';
          break;
        // Add more cases as needed...
        default:
          $svg = '';
      }

      $table .= '<tr><td><strong>' . $svg . $field_name . '</strong></td><td class="custom-value"><strong>' . esc_attr($field_value) . '</strong></td></tr>';
    }
  }

  $table .= '</tbody></table>';

  if (!$hasValue) {
    return false;
  }

  return $table;
}

function prefix_generate_table_3($fields)
{
  $table = '<table><tbody><tr>';

  foreach ($fields as $field) {
    $field_value = strtolower(get_post_meta(get_the_ID(), $field, true));

    if ($field_value) {
      // Add different SVGs depending on the field value
      $svg = displaySVGFiles($field_value);

      if ($svg) {
        // Modify the SVG height and width attributes
        $modifiedSvg = str_replace('<svg', '<svg height="35" width="35"', $svg);
        $table .= '<td style="vertical-align: middle; text-align: center;" class="custom-value"><strong>' . $modifiedSvg . $field_value . '</strong></td>';
      } else {
        $table .= '<td class="custom-value"><strong>' . $field_value . '</strong></td>';
      }
    }
  }

  $table .= '</tr></tbody></table>';

  return $table;
}

function cat_is_strains()
{
  $cats = get_the_category();
  foreach ($cats as $cat) {
    if (strtolower($cat->name) == 'strains') {
      $isStrains = true;
    }
  }
  return $isStrains;

}