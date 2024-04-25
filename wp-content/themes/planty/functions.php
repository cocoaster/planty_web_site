<?php

// Fontion qui permet de charger des fichiers css dans le thème
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles(){
    // Chargement du style.css du thème parent Hello
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('theme-style', get_stylesheet_directory_uri() . '/css/theme.css', array(), filemtime(get_stylesheet_directory() . '/css/theme.css'));
}

// Générer dynamiquement la liste des produits avec les contrôles de quantité
function insert_product_with_quantity_controls() {
    
    $args = array(
        'post_type' => 'produits',
        'posts_per_page' => -1,
        'orderby' => 'date', // Trie par date
        'order' => 'ASC' // Ordre croissant
    );
    $query = new WP_Query($args);

    $html = '<div class="container-of-product-quantity-blocks">';

    while ($query->have_posts()): $query->the_post();
        $product_id = get_the_ID();
        $image_id = get_field('image', $product_id);
        $image_url = wp_get_attachment_image_url($image_id, 'full');
        $name = get_field('nom_du_produit', $product_id);

        // Echapper d'abord le contenu dynamique
        $name = esc_html($name);

        // Modification du nom du produit si nécessaire
        if ($name === 'PAMPLEMOUSSE') {
            $name = 'PAMPLE<br>MOUSSE';
        } elseif ($name === 'FRAMBOISE') {
            $name = 'FRAM<br>BOISE';
        }

        $html .= '<div class="product-quantity-block" id="product-' . esc_attr($product_id) . '">';
        $html .= '<div class="product-image-and-number">';

        $html .= '<div class="product-image-container">';
        $html .= $image_url ? '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($name) . '" class="product-image"/>' : '<img src="' . esc_url(get_stylesheet_directory_uri() . '/images/default-product.jpg') . '" alt="Image not available" class="product-image"/>'; // Fournir une image par défaut si aucune image de produit n'est disponible
        $html .= '</div>';
       
        $html .= '<div class="product-name">' . $name . '</div>';
        $html .= '</div>'; // Fin de .fruit-image-container

        $html .= '<div class="quantity-controls">';
        $html .= '<input type="number" class="quantity-input" data-product-id="' . esc_attr($product_id) . '" data-product-name="' . esc_attr($name) . '" name="quantity_' . esc_attr($product_id) . '" value="0" min="0"/>';
        $html .= '<div class="quantity-buttons">';
        $html .= '<button type="button" class="quantity-change quantity-plus" data-product-id="' . esc_attr($product_id) . '" data-product-name="' . esc_attr($name) . '">+</button>';
        $html .= '<button type="button" class="quantity-change quantity-minus" data-product-id="' . esc_attr($product_id) . '" data-product-name="' . esc_attr($name) . '">-</button>';
        $html .= '</div>'; 
        
        
        $html .= '<div class="submit-container">';
        $html .= '<button type="button" class="submit-quantity" data-product-id="' . esc_attr($product_id) . '" data-product-name="' . esc_attr($name) . '">OK</button>';
        $html .= '</div>';// Fin de .product-image-and-number

        $html .= '</div>';
        $html .= '</div>';
    endwhile;

    $html .= '</div>';

    wp_reset_postdata();

    return $html;
}
add_shortcode('product_quantities', 'insert_product_with_quantity_controls');



// Fonction pour ajouter l'item admin quand un usager est conneecter à wordpress
function add_admin_menu_item_conditionally( $items, $args ) {
    if (is_user_logged_in() && 'primary' === $args->theme_location) {
        // Créer le nouvel élément de menu
        $new_item = '<li class="menu-item"><a href="' . admin_url() . '">Admin</a></li>';
        
        // Diviser les éléments de menu existants en un tableau
        $menu_items = explode('</li>', $items);
        
        // Insértion du nouvel élément à la position 2
        array_splice($menu_items, 1, 0, $new_item); // Le 1 ici indique la position après le premier élément
        
        // Recombiner les éléments de menu en une chaîne
        $items = implode('</li>', $menu_items);
    }

    return $items;
}
add_filter( 'wp_nav_menu_items', 'add_admin_menu_item_conditionally', 100, 2 );




// Traiter les champs dynamiques avant l'envoi du courrier
function custom_dynamic_field_in_mail_content($contact_form) {
    $submission = WPCF7_Submission::get_instance();
    if ($submission) {
        $data = $submission->get_posted_data();
        $dynamic_fields_info = "";
        $products_info = array(); // Initialise le tableau pour éviter des erreurs si vide.

        foreach ($data as $name => $value) {
            if (strpos($name, 'product-') === 0) {
                list(, $id, $fieldType) = explode('-', $name); // Sécurisation de l'extraction des données
                $products_info[$id][$fieldType] = $value;
            }
        }

        foreach ($products_info as $info) {
            if (!empty($info['name']) && isset($info['quantity'])) {
                // Supprime la balise <br> du nom du produit
                $productName = str_replace('<br>', '', $info['name']);
                $productName = sanitize_text_field($productName); // Nettoie le nom pour la sécurité
                $quantity = intval($info['quantity']); // Vérifie que la quantité est un nombre
                $dynamic_fields_info .= "Produit : " . $productName . " - Quantité : " . $quantity . "\n";
            }
        }

        // Remplace le marqueur dans le corps de l'email par les informations dynamiques des produits
        $mail = $contact_form->prop('mail');
        $mail['body'] = str_replace('[dynamic-fields-info]', $dynamic_fields_info, $mail['body']);
        $contact_form->set_properties(['mail' => $mail]);
    }
}
add_action('wpcf7_before_send_mail', 'custom_dynamic_field_in_mail_content');

function my_form_submission_handler($contact_form) {
    // Après le traitement, rediriger vers une URL spécifique sans renvoyer de données au rechargement de la page
    $url = 'confirmation_page.php?submission=success'; // URL spécifique avec ancre

    wp_redirect($url); // Effectue la redirection vers l'URL spécifiée
    exit(); // Arrête l'exécution du script pour s'assurer que rien d'autre n'est exécuté après la redirection
}


function ajouter_mon_script() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
    const minusButtons = document.querySelectorAll('.quantity-minus');
    const plusButtons = document.querySelectorAll('.quantity-plus');
    let isSubmitting = false; // Ajout d'un indicateur de soumission

    const adjustQuantity = (button, increment) => {
        const productID = button.getAttribute('data-product-id');
        const quantityField = document.querySelector('.quantity-input[data-product-id="' + productID + '"]');
        let currentValue = parseInt(quantityField.value, 10) || 0;
        currentValue += increment;
        if (currentValue < 0) {
            currentValue = 0; // Empêche les quantités négatives
        }
        quantityField.value = currentValue;
        quantityField.setAttribute('aria-valuenow', currentValue);
    };

    minusButtons.forEach(button => {
        button.addEventListener('click', () => adjustQuantity(button, -1));
    });

    plusButtons.forEach(button => {
        button.addEventListener('click', () => adjustQuantity(button, 1));
    });

    const form = document.querySelector('.wpcf7-form');
    if (form) {
        form.addEventListener('submit', function(event) {
            if (!isSubmitting) {
                event.preventDefault();
                isSubmitting = true; // Marque que la soumission a commencé

                const container = document.getElementById('dynamic-fields-container');
                container.innerHTML = ''; // Nettoie les champs précédents

                const quantityFields = document.querySelectorAll('.quantity-input');
                quantityFields.forEach(field => {
                    const productId = field.getAttribute('data-product-id');
                    const productName = field.getAttribute('data-product-name');
                    const quantity = field.value;

                    let hiddenQuantityInput = document.createElement('input');
                    hiddenQuantityInput.type = 'hidden';
                    hiddenQuantityInput.name = `product-${productId}-quantity`;
                    hiddenQuantityInput.value = quantity;
                    container.appendChild(hiddenQuantityInput);

                    let hiddenNameInput = document.createElement('input');
                    hiddenNameInput.type = 'hidden';
                    hiddenNameInput.name = `product-${productId}-name`;
                    hiddenNameInput.value = productName;
                    container.appendChild(hiddenNameInput);
                });

                // Utilisation de setTimeout pour gérer des tâches asynchrones ou des mises à jour de DOM qui doivent être achevées avant la soumission
                setTimeout(() => {
                    form.submit(); // Soumettre le formulaire après avoir assuré que tout est prêt
                }, 100); 
            }
        });
    }
});

// Ajout de l'item Admin dans le menu mobile
jQuery(document).ready(function($) {
    var adminUrl = "<?php echo esc_url(admin_url()); ?>";
    var newItem = '<li class="menu-item"><a href="' + adminUrl + '">Admin</a></li>';
    
    if ( $(window).width() < 1068 ) {
        $('#mobmenuright li').eq(0).after(newItem); // Ajoute newItem après le premier élément li
    }
});

    </script>
    <?php
}
add_action('wp_footer', 'ajouter_mon_script');
