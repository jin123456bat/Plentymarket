<?php

namespace Plentymarket\Services;

use Plentymarket\Helper\Utils;

/**
 * Class TranslateService
 * @package Plentymarket\Services
 */
class TranslateService
{
	/**
	 * @var array[]
	 */
	private $language = [
		'it' => [
			'ApiIndex' => [
				'registerEmailExist' => 'la mail è già esistente',
				'registerSuccess' => 'la registrazione è andata a successo',
				'registerEmailOrPasswordError' => 'email o password non possono essere vuoti',
				'loginSuccess' => 'l’accesso con successo',
				'loginEmailOrPasswordError' => 'nome utente o password errato',
			],
			'Common' => [
				'home' => 'Home page',
				'contact' => 'Contattaci',
				'moreCategories' => 'Altre categorie',
				'browseCategories' => 'Sfoglia le categorie',
				'my_cart' => 'carrello',
				'wishlist' => 'Lista dei desideri',
				'view_cart' => 'carrello',
				'checkout' => 'check out',
				'cart_empty' => 'Il carrello è vuoto',
				'my_account' => 'il mio account',
				'welcome' => 'Messaggio di benvenuto',
				'about' => 'Chi siamo',
				'blog' => 'informazione',
				'copyright' => 'Copyright @ 2020 MercuryLiving. Tutti i diritti riservati',
				'search_text' => 'Cerca',
			],
			'WebAccountCart' => [
				'cart' => 'carrello',
				'discount_coupon_code' => 'Codice promozionale',
				'coupon_code' => 'Codice promozionale',
				'apply_code' => 'Usa codice promozionale',
				'image' => 'immagine',
				'product' => 'Nome',
				'price' => 'Prezzo',
				'quantity' => 'Quantità',
				'total' => 'Totale',
				'remove' => 'Elimina',
				'vat' => 'IVA',
				'cart_summary' => 'Sommario',
				'sub_total' => 'Sommario',
				'shipping_cost' => 'Spese di spedizione',
				'grand_total' => 'Totale',
				'checkout' => 'check out',
				'update_cart' => 'Aggiorna il carrello',
				'coupon_not_avaliable' => 'Il codice coupon non è disponibile',
				'shopping_basket' => 'carrello',
				'basket_empty_desc' => 'Il carrello è vuoto',
				'continue_shopping' => 'continua a fare acquisti',
			],
			'WebAccountCheckout' => [
				'checkout' => 'check out',
				'create_address_failed' => 'Creazione dell\'indirizzo non riuscita',
				'accept_terms' => 'Accetta prima i termini per favore',
				'choice_payment' => 'Scegli un metodo di pagamento',
			],
			'WebAccountIndex' => [
				'account' => 'il mio account',
				'orders' => 'Ordine',
				'address' => 'Indirizzo',
				'logout' => 'Logout',
				'view' => 'Visualizza',
				'mobile' => 'Mobile',
			],
			'WebAccountWishlist' => [
				'wishlist' => 'Lista dei desideri',
				'image' => 'immagine',
				'product' => 'Nome',
				'price' => 'Prezzo',
				'quantity' => 'Quantità',
				'total' => 'Totale',
				'remove' => 'Elimina',
				'vat' => 'IVA',
				'wishlist_empty_desc' => 'La lista dei desideri è ancora vuota',
				'continue' => 'Aggiungi',
			],
			'WebIndexAbout' => [
				'about' => 'Chi siamo',
			],
			'WebIndexBlog' => [
				'blog' => 'consulta',
				'share_this_post' => 'Condividi questo articolo ',
			],
			'WebIndexBlogList' => [
				'blog' => 'consulta',
				'continue' => 'continua',
			],
			'WebIndexContact' => [
				'contact' => 'Referente',
				'tell_us_your_message' => 'Dicci il tuo messaggio',
				'your_name' => 'Il tuo nome',
				'your_message' => 'Il tuo messaggio',
				'subject' => 'Oggetto',
				'your_email' => 'La tua email',
				'email' => 'Email',
				'address' => 'Indirizzo',
				'phone' => 'Telefono',
			],
			'WebIndexFaq' => [
				'faq' => 'FAQ',
			],
			'WebIndexIndex' => [
				'read_more' => 'Leggi di più',
				'our_blog_posts' => 'I Nostri <span>  Post <span> Sul Blog',
				'our_blog_posts_desc' => 'Vuoi presentare i post nel modo migliore per evidenziare momenti interessanti del tuo blog?',
				'top_selling_products' => 'Prodotti <span> Più <span> Venduti',
				'top_selling_products_desc' => 'Sfoglia la collezione dei nostri bestseller, troverai sicuramente quello che stai cercando',
				'deals_of_the_week' => 'Transazioni <span> Della <span> Settimana',
				'deals_of_the_week_desc' => 'Le transazioni della settimana sono una selezione di nuove transazioni aggiornate ogni settimana!',
				'full_banner' => 'Il tuo compito è avere l\'idea. È nostro compito per realizzarla.',
				'full_banner_desc' => 'Siamo un produttore di mobili italiano che aiuta le persone a dare vita alle loro idee.',
				'all_products' => ' Guarda i nostri prodotti',
				'new_collections_of_arrivals' => 'nuove <span> collezioni <span> di arrivi',
				'new_collections_of_arrivals_desc' => 'Sfoglia la collezione dei nostri bestseller, troverai sicuramente quello che stai cercando',
				'featured_category' => 'categoria <span> in evidenza<span>',
				'featured_category_desc' => 'Mostra tutte le categorie in primo piano con prodotti sulla home page.',
				'free_shipping' => 'Free Shipping <span>Free shipping on all US order</span>',
				'support' => 'Supporto 24/7 <span> Contattaci 24 ore al giorno </span>',
				'return' => 'Rimborso del 100% <span> Hai 14 giorni per tornare </span>',
				'payment' => 'Pagamento sicuro <span> Garantiamo un pagamento sicuro </span>',
				'popular_products' => 'Alcuni </span> prodotti <span> popolari',
				'popular_products_desc' => 'Offriamo i migliori mobili di selezione a prezzi che amerai!',
			],
			'WebIndexLoginRegister' => [
				'login' => 'accedi',
				'emailAddress' => 'indirizzo email',
				'password' => 'password',
				'rememberMe' => 'ricorda di me',
				'forgottenPassword' => 'dimenticare la password',
				'register' => 'Registrati',
				'confirmPassword' => 'conferma password',
				'loginRegister' => ' Register   accedi/Registrati',
				'emailUsed' => 'L\'account e-mail è stato registrato',
				'emailOrPasswordError' => 'La mail o la password non è corretta',
				'RepasswordError' => 'due password sono incoerenti',
			],
			'WebIndexProduct' => [
				'quantity' => 'Quantità',
				'add_to_cart' => 'Aggiungi al carrello',
				'add_to_wishlist' => 'Aggiungi alla lista dei desideri',
				'remove_from_wishlist' => 'Rimuovi dalla lista dei desideri',
				'add_to_cart_success' => 'Aggiungi carrello con successo',
				'add_to_cart_failed' => ' Aggiungi al carrello non riuscito',
				'add_to_wishlist_success' => 'Lista dei desideri aggiunta correttamente',
				'add_to_wishlist_failed' => 'Impossibile aggiungere la lista dei desideri',
			],
			'WebIndexProductListCategory' => [
				'previous' => 'Pagina precedente',
				'next' => 'Prossimo',
				'add_to_cart' => 'Aggiungi al carrello',
				'share_this_product' => 'Condividi beni'
			]
		],
		'en' => [
			'ApiIndex' => [
				'registerEmailExist' => 'the email already exists',
				'registerSuccess' => 'registration was successful',
				'registerEmailOrPasswordError' => 'email or password cannot be empty',
				'loginSuccess' => 'login successful',
				'loginEmailOrPasswordError' => 'username or password incorrect',
			],
			'Common' => [
				'home' => 'Home',
				'contact' => 'Contact Us',
				'moreCategories' => 'More categories',
				'browseCategories' => 'Browse categories',
				'my_cart' => 'Cart',
				'wishlist' => 'Wishlist',
				'view_cart' => 'Cart',
				'checkout' => 'Checkout',
				'cart_empty' => 'shopping cart is empty',
				'my_account' => 'Account',
				'welcome' => 'Welcome Message',
				'about' => 'About Us',
				'blog' => 'News',
				'copyright' => 'Copyright @ 2020 MercuryLiving. All Rights Reserved',
				'search_text' => 'search',
			],
			'WebAccountCart' => [
				'cart' => 'Cart',
				'discount_coupon_code' => 'Promo Code',
				'coupon_code' => 'Promo Code',
				'apply_code' => 'Apply Promo Code',
				'image' => 'Image',
				'product' => 'Name',
				'price' => 'Price',
				'quantity' => 'Quantity',
				'total' => 'Total',
				'remove' => 'Remove',
				'vat' => 'VAT',
				'cart_summary' => 'Summary',
				'sub_total' => 'Total',
				'shipping_cost' => 'Shipping ',
				'grand_total' => 'Total',
				'checkout' => 'Checkout',
				'update_cart' => 'Update Cart',
				'coupon_not_avaliable' => 'coupon code is not available',
				'shopping_basket' => 'Cart',
				'basket_empty_desc' => 'shopping cart is empty.',
				'continue_shopping' => 'continue shopping',
			],
			'WebAccountCheckout' => [
				'checkout' => 'Checkout',
				'create_address_failed' => 'Address creation failed',
				'accept_terms' => 'Please accept the terms first',
				'choice_payment' => 'Please choose a payment method',
			],
			'WebAccountIndex' => [
				'account' => 'Account',
				'orders' => 'Orders',
				'address' => 'Address',
				'logout' => 'Logout',
				'view' => 'View',
				'mobile' => 'Mobile',
			],
			'WebAccountWishlist' => [
				'wishlist' => 'Wishlist',
				'image' => 'Image',
				'product' => 'Name',
				'price' => 'Price',
				'quantity' => 'Quantity',
				'total' => 'Total',
				'remove' => 'Remove',
				'vat' => 'VAT',
				'wishlist_empty_desc' => 'Wishlist is still empty',
				'continue' => 'Add',
			],
			'WebIndexAbout' => [
				'about' => 'About us',
			],
			'WebIndexBlog' => [
				'blog' => 'Consult',
				'share_this_post' => 'Share this article',
			],
			'WebIndexBlogList' => [
				'blog' => 'Consult',
				'continue' => 'Continue',
			],
			'WebIndexContact' => [
				'contact' => 'Contact person',
				'tell_us_your_message' => 'Tell Us Your Message',
				'your_name' => 'Your Name',
				'your_message' => 'Your Message',
				'subject' => 'Subject',
				'your_email' => 'Your Email',
				'email' => 'Email',
				'address' => 'Address',
				'phone' => 'Phone',
			],
			'WebIndexFaq' => [
				'faq' => 'FAQ',
			],
			'WebIndexIndex' => [
				'read_more' => 'Read More',
				'our_blog_posts' => 'Our <span>Blog</span> Posts',
				'our_blog_posts_desc' => 'Do you want to present posts in the best way to highlight interesting moments of your blog?',
				'top_selling_products' => 'Top <span>Selling</span> Products',
				'top_selling_products_desc' => 'Browse the collection of our top selling, You will definitely find what you are looking for.',
				'deals_of_the_week' => 'Deals <span>of The</span> Week',
				'deals_of_the_week_desc' => 'Deals of the Week are a selection of fresh deals updated every week!',
				'full_banner' => 'It\'s your job to have the idea. It\'s ours to make it happen.',
				'full_banner_desc' => 'We are a italian furniture maker helping people bring their ideas to life.',
				'all_products' => 'View our products',
				'new_collections_of_arrivals' => 'New <span>Collections</span> Of Arrivals',
				'new_collections_of_arrivals_desc' => 'Browse the collection of our new products, You will definitely find what you are looking for.',
				'featured_category' => 'Featured <span>Categories</span>',
				'featured_category_desc' => 'Show all featured categories with products on home page.',
				'free_shipping' => 'Free Shipping <span>Free shipping on all US order</span>',
				'support' => 'Support 24/7 <span>Contact us 24 hours a day</span>',
				'return' => '100% Money Back <span>You have 14 days to Return</span>',
				'payment' => 'Payment Secure <span>We ensure secure payment</span>',
				'popular_products' => 'Some <span>Popular</span> Products',
				'popular_products_desc' => 'We offer the best selection furniture at prices you will love!',
			],
			'WebIndexLoginRegister' => [
				'login' => 'Login',
				'emailAddress' => 'email address',
				'password' => 'password',
				'rememberMe' => 'remember me',
				'forgottenPassword' => 'forget password',
				'register' => 'register',
				'confirmPassword' => 'confirm password',
				'loginRegister' => 'login/register',
				'emailUsed' => 'Email account has been registered',
				'emailOrPasswordError' => 'Mail or password is incorrect',
				'RepasswordError' => 'two passwords are inconsistent',
			],
			'WebIndexProduct' => [
				'quantity' => 'Quantity',
				'add_to_cart' => 'Add to cart',
				'add_to_wishlist' => 'Add to wishlist',
				'remove_from_wishlist' => 'Remove from wishlist',
				'add_to_cart_success' => 'Add cart successfully',
				'add_to_cart_failed' => 'Add to Cart failed',
				'add_to_wishlist_success' => 'Successfully added wishlist',
				'add_to_wishlist_failed' => 'Failed to add wishlist',
			],
			'WebIndexProductListCategory' => [
				'previous' => 'Previous',
				'next' => 'Next',
				'add_to_cart' => 'Add to cart',
				'share_this_product' => 'Share goods',
			]
		]
	];

	/**
	 * @param string $key
	 * @return string
	 */
	function trans ($key): string
	{
		$lang = Utils::getLang();
		if ($lang == 'de') {
			$lang = 'en';
		}
		list($k1, $k2) = explode('.', $key, 2);
		list($v1, $v2) = explode('::', $k1, 2);
		return $this->language[$lang][$v2][$k2] ?? $this->language['en'][$v2][$k2] ?? '';
	}
}
