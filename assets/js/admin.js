(window.webpackJsonp=window.webpackJsonp||[]).push([[2],{43:function(e,t,n){"use strict";n.r(t);var a=n(0),s={name:"App"},i=n(3),r=Object(i.a)(s,(function(){var e=this.$createElement,t=this._self._c||e;return t("div",{attrs:{id:"vue-backend-app"}},[t("h1",[this._v("Config Page Example Vue")]),this._v(" "),t("router-view")],1)}),[],!1,null,null,null).exports,u=n(12),l={name:"Home",data:()=>({msg:"Welcome to Your Vue.js Admin App"})},p=Object(i.a)(l,(function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"home"},[t("span",[this._v(this._s(this.msg))])])}),[],!1,null,"1be020c0",null).exports,o={name:"Settings",data:()=>({})},c=Object(i.a)(o,(function(){var e=this.$createElement;return(this._self._c||e)("div",{staticClass:"app-settings"},[this._v("\n  The Settings Page\n")])}),[],!1,null,"9b386344",null).exports;a.default.use(u.a);var d=new u.a({routes:[{path:"/",name:"Home",component:p},{path:"/settings",name:"Settings",component:c}]});var h=function(e){var t=jQuery;let n=t("#toplevel_page_"+e),a=window.location.href,s=a.substr(a.indexOf("admin.php"));n.on("click","a",(function(){var e=t(this);t("ul.wp-submenu li",n).removeClass("current"),e.hasClass("wp-has-submenu")?t("li.wp-first-item",n).addClass("current"):e.parents("li").addClass("current")})),t("ul.wp-submenu a",n).each((function(e,n){t(n).attr("href")!==s||t(n).parent().addClass("current")}))};a.default.config.productionTip=!1,new a.default({el:"#vue-admin-app",router:d,render:e=>e(r)}),h("vue-app")}},[[43,0,1]]]);