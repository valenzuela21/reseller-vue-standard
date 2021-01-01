import Vue from 'vue';

const URL = window.location.hostname;
const SLL = window.location.protocol;

let currentUrl = window.location.href;
let currentPath = currentUrl.substr(currentUrl.indexOf('?post='));
currentPath = currentPath.split("&");
currentPath = currentPath[0].split("=");

let urlConsultCategory, urlConsultGeneral, urlConsultReseller, urlConsulTendero, urlDeleteReseller, urlConsultSelectReseller, urlInsertNewReseller, urlConsultResellerMetabox, urlCategoryProduct, urlSelectCategory, urlSearchCategory, urlSearchProducts, urlDeleteProductReseller, urlSearchGeneral, urlVariableProduct, urlProductRelation, urlStartProduct, urlVariableImage;

if (URL === 'localhost') {
  //Frontend Consult Url
  urlConsultCategory = `${SLL}//${URL}:3000/wordpress/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_category_tendero.php`;
  urlConsultGeneral = `${SLL}//${URL}:3000/wordpress/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_general_tendero.php`;
  urlConsultReseller = `${SLL}//${URL}:3000/wordpress/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_productos_reseller.php`;
  urlConsulTendero = `${SLL}//${URL}:3000/wordpress/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_tendero.php`;
  urlCategoryProduct = `${SLL}//${URL}:3000/wordpress/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_category_product.php`;
  urlSelectCategory = `${SLL}//${URL}:3000/wordpress/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_category_product_select.php`;
  urlSearchCategory = `${SLL}//${URL}:3000/wordpress/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_search_product_category.php`;
  urlSearchProducts = `${SLL}//${URL}:3000/wordpress/wp-content/plugins/master-tendero-woocommerce/includes/Api/consult_reseller_products.php`;
  urlSearchGeneral = `${SLL}//${URL}:3000/wordpress/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/search_product.php`;
  urlVariableProduct = `${SLL}//${URL}:3000/wordpress/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_product_variable.php`;
  urlProductRelation = `${SLL}//${URL}:3000/wordpress/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_products_relations.php`;
  urlStartProduct = `${SLL}//${URL}:3000/wordpress/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_start_product.php`;
  urlVariableImage =  `${SLL}//${URL}:3000/wordpress/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_image_variable_product.php`

  //Admin Backend Url CRUD
  urlDeleteReseller = `${SLL}//${URL}:3000/wordpress/wp-content/plugins/master-tendero-woocommerce/includes/Api/delete_item_reseller.php`;
  urlConsultSelectReseller = `${SLL}//${URL}:3000/wordpress/wp-content/plugins/master-tendero-woocommerce/includes/Api/consult_select_tendero.php`;
  urlInsertNewReseller = `${SLL}//${URL}:3000/wordpress/wp-content/plugins/master-tendero-woocommerce/includes/Api/insert_new_reseller.php`;
  urlConsultResellerMetabox = `${SLL}//${URL}:3000/wordpress/wp-content/plugins/master-tendero-woocommerce/includes/Api/consult_shopping_success.php`;
  urlDeleteProductReseller = `${SLL}//${URL}:3000/wordpress/wp-content/plugins/master-tendero-woocommerce/includes/Api/delete_item_product_reseller.php`;

} else {
  //Frontend Consult Url
  urlConsultCategory = `${SLL}//${URL}/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_category_tendero.php`;
  urlConsultGeneral = `${SLL}//${URL}/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_general_tendero.php`;
  urlConsultReseller = `${SLL}//${URL}/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_productos_reseller.php`;
  urlConsulTendero = `${SLL}//${URL}/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_tendero.php`;
  urlCategoryProduct = `${SLL}//${URL}/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_category_product.php`;
  urlSelectCategory = `${SLL}//${URL}/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_category_product_select.php`;
  urlSearchCategory = `${SLL}//${URL}/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_search_product_category.php`;
  urlSearchProducts = `${SLL}//${URL}/wp-content/plugins/master-tendero-woocommerce/includes/Api/consult_reseller_products.php`;
  urlSearchGeneral = `${SLL}//${URL}/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/search_product.php`;
  urlVariableProduct = `${SLL}//${URL}/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_product_variable.php`;
  urlProductRelation = `${SLL}//${URL}/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_products_relations.php`;
  urlStartProduct = `${SLL}//${URL}/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_start_product.php`;
  urlVariableImage = `${SLL}//${URL}/wp-content/plugins/master-tendero-woocommerce/includes/Api/frontend/consult_image_variable_product.php`;

  //Admin Backend Url CRUD
  urlDeleteReseller = `${SLL}//${URL}/wp-content/plugins/master-tendero-woocommerce/includes/Api/delete_item_reseller.php`;
  urlConsultSelectReseller = `${SLL}//${URL}/wp-content/plugins/master-tendero-woocommerce/includes/Api/consult_select_tendero.php`;
  urlInsertNewReseller = `${SLL}//${URL}/wp-content/plugins/master-tendero-woocommerce/includes/Api/insert_new_reseller.php`;
  urlConsultResellerMetabox = `${SLL}//${URL}/wp-content/plugins/master-tendero-woocommerce/includes/Api/consult_shopping_success.php`;
  urlDeleteProductReseller = `${SLL}//${URL}/wp-content/plugins/master-tendero-woocommerce/includes/Api/delete_item_product_reseller.php`;

}

//Frontend PropType Consult Url
Vue.prototype.urlConsultCategory = urlConsultCategory;
Vue.prototype.urlConsultGeneral = urlConsultGeneral;
Vue.prototype.urlConsultReseller = urlConsultReseller;
Vue.prototype.urlConsulTendero = urlConsulTendero;
Vue.prototype.urlCategoryProduct = urlCategoryProduct;
Vue.prototype.urlSelectCategory = urlSelectCategory;
Vue.prototype.urlSearchCategory = urlSearchCategory;
Vue.prototype.urlSearchProducts = urlSearchProducts;
Vue.prototype.urlSearchGeneral = urlSearchGeneral;
Vue.prototype.urlVariableProduct = urlVariableProduct;
Vue.prototype.urlProductRelation = urlProductRelation;
Vue.prototype.urlStartProduct = urlStartProduct;
Vue.prototype.urlVariableImage = urlVariableImage;


//Backend PropType Consult Url
Vue.prototype.urlDeleteReseller = urlDeleteReseller;
Vue.prototype.urlConsultSelectReseller = urlConsultSelectReseller;
Vue.prototype.urlInsertNewReseller = urlInsertNewReseller;
Vue.prototype.urlConsultResellerMetabox = urlConsultResellerMetabox;
Vue.prototype.urlDeleteProductReseller = urlDeleteProductReseller;