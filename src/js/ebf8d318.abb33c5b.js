(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["ebf8d318"],{2962:function(t,s,a){},"5b6e":function(t,s,a){"use strict";a.r(s);var e=function(){var t=this,s=t.$createElement,a=t._self._c||s;return a("q-layout",{staticClass:"primary-layout",attrs:{id:"primaryLayout",view:"HHh Lpr lFf",classx:{"bg-mc dark-ui ":t.isLight,"dark-ui":t.isDark,warn:!t.isConnected}}},[a("q-header",{staticClass:"hidden"},[a("q-toolbar",{attrs:{color:"black"}},[a("q-btn",{attrs:{flat:"",dense:"",round:"","aria-label":"Menu"},on:{click:function(s){t.miniModeOn=!t.miniModeOn}}},[a("q-icon",{attrs:{name:"menu"}})],1),a("q-toolbar-title",{staticClass:"glow text-dark"},[t._v("\n        ADA\n        ")]),a("q-btn-dropdown",{attrs:{flat:"",round:"",dense:"",icon:"more_vert","icon-right":""}},[a("q-list",{attrs:{link:""}},[a("q-item",{attrs:{name:"logout"},nativeOn:{click:function(s){return t.logout(s)}}},[a("q-item-section",{attrs:{avatar:""}},[a("q-icon",{attrs:{name:"fas fa-sign-out-alt",color:"primary"}})],1),a("q-item-section",[a("q-item-label",{attrs:{label:""}},[t._v("Logoutx")])],1)],1)],1)],1)],1)],1),a("q-drawer",{staticClass:"drawer-border",attrs:{id:"primaryLayoutDrawer",mini:t.miniModeOn,behaviour:"desktop",breakpoint:t.breakpoint,"content-class":{"bg-mc":t.isLight,"bg-dark":!t.isLight},"content-style":{overflow:"visible!important"}},on:{mouseover:t.hideTextProcess,mouseout:t.hideTextProcess},model:{value:t.leftDrawerOpen,callback:function(s){t.leftDrawerOpen=s},expression:"leftDrawerOpen"}},[a("q-toolbar-title",{staticClass:"q-pt-md q-ml-sm text-primary",staticStyle:{"text-align":"center"}},[a("img",{attrs:{width:"20px",src:"/statics/icons/plain.svg"}})]),a("q-list",{staticClass:"q-mt-sm text-primary",class:{hideText:t.hideText},attrs:{"no-border":"",link:"","inset-":""}},[a("router-link",{attrs:{to:"/",exact:""}},[a("q-item",{directives:[{name:"hotkey",rawName:"v-hotkey",value:{"ctrl+shift+h":function(){t.go("/")}},expression:"{'ctrl+shift+h': () => {go('/')}}"}],staticClass:"q-pa-xs"},[a("q-item-section",{staticClass:"flex-center",attrs:{side:""}},[a("q-icon",{attrs:{name:"fal fa-home"}}),a("p",{staticClass:"menu-caption"},[a("span",{staticClass:"shortcut"},[t._v("H")]),t._v("ome")])],1)],1)],1),t.hasModuleAccess("comms")?a("router-link",{attrs:{to:"/",exact:""}},[a("q-item",{staticClass:"q-pa-xs",attrs:{count:"3"}},[a("q-item-section",{staticClass:"flex-center",attrs:{side:""}},[a("q-icon",{attrs:{name:"fal fa-envelope"}}),a("p",{staticClass:"menu-caption"},[a("span",{staticClass:"shortcut"},[t._v("C")]),t._v("omms")])],1)],1)],1):t._e(),t.hasModuleAccess("students")?a("router-link",{attrs:{to:"/students",exact:""}},[a("q-item",{directives:[{name:"hotkey",rawName:"v-hotkey.once",value:{"ctrl+shift+s":function(){t.go("/students")}},expression:"{'ctrl+shift+s': () => {go('/students')}}",modifiers:{once:!0}}],staticClass:"q-pa-xs"},[a("q-item-section",{staticClass:"flex-center",attrs:{side:""}},[a("q-icon",{attrs:{name:"fal fa-child"}}),a("p",{staticClass:"menu-caption"},[a("span",{staticClass:"shortcut"},[t._v("S")]),t._v("tudents")])],1)],1)],1):t._e(),t.hasModuleAccess("hm")?a("router-link",{attrs:{to:"/hm",exact:""}},[a("q-item",{staticClass:"q-pa-xs"},[a("q-item-section",{staticClass:"flex-center",attrs:{side:""}},[a("q-icon",{attrs:{name:"fal fa-paw-claws"}}),a("p",{staticClass:"menu-caption"},[a("span",{staticClass:"shortcut"},[t._v("H")]),t._v("M")])],1)],1)],1):t._e(),t.hasModuleAccess("accounts")?a("router-link",{attrs:{to:"/accounts",exact:""}},[a("q-item",{staticClass:"q-pa-xs"},[a("q-item-section",{staticClass:"flex-center",attrs:{side:""}},[a("q-icon",{attrs:{name:"fal fa-credit-card"}}),a("p",{staticClass:"menu-caption"},[a("span",{staticClass:"shortcut"},[t._v("A")]),t._v("ccounts")])],1)],1)],1):t._e(),t.hasModuleAccess("beaks")?a("router-link",{attrs:{to:"/",exact:""}},[a("q-item",{staticClass:"q-pa-xs"},[a("q-item-section",{staticClass:"flex-center",attrs:{side:""}},[a("q-icon",{attrs:{name:"fal fa-graduation-cap"}}),a("p",{staticClass:"menu-caption"},[a("span",{staticClass:"shortcut"},[t._v("T")]),t._v("eachers")])],1)],1)],1):t._e(),t.hasModuleAccess("data")?a("router-link",{staticClass:"bg-black",attrs:{to:"/data",exact:""}},[a("q-item",{staticClass:"q-pa-xs"},[a("q-item-section",{staticClass:"flex-center",attrs:{side:""}},[a("q-icon",{attrs:{name:"fal fa-chart-pie"}}),a("p",{staticClass:"menu-caption"},[a("span",{staticClass:"shortcut"},[t._v("D")]),t._v("ata")])],1)],1)],1):t._e(),t.hasModuleAccess("bookings")?a("router-link",{staticClass:"bg-black",attrs:{to:"/",exact:""}},[a("q-item",{staticClass:"q-pa-xs"},[a("q-item-section",{staticClass:"flex-center",attrs:{side:""}},[a("q-icon",{attrs:{name:"fal fa-calendar-check"}}),a("p",{staticClass:"menu-caption"},[a("span",{staticClass:"shortcut"},[t._v("B")]),t._v("ookings")])],1)],1)],1):t._e(),t.hasModuleAccess("exams")?a("router-link",{staticClass:"bg-black",attrs:{to:"/exams"}},[a("q-item",{staticClass:"q-pa-xs"},[a("q-item-section",{staticClass:"flex-center",attrs:{side:""}},[a("q-icon",{attrs:{name:"fal fa-university"}}),a("p",{staticClass:"menu-caption"},[a("span",{staticClass:"shortcut"},[t._v("E")]),t._v("xams")])],1)],1)],1):t._e(),t.hasModuleAccess("location")?a("router-link",{staticClass:"bg-black",attrs:{to:"/location"}},[a("q-item",{staticClass:"q-pa-xs"},[a("q-item-section",{staticClass:"flex-center",attrs:{side:""}},[a("q-icon",{attrs:{name:"fal fa-location"}}),a("p",{staticClass:"menu-caption"},[a("span",{staticClass:"shortcut"},[t._v("L")]),t._v("ocation")])],1)],1)],1):t._e(),t.hasModuleAccess("transport")?a("router-link",{staticClass:"bg-black",attrs:{to:"/transport"}},[a("q-item",{directives:[{name:"hotkey",rawName:"v-hotkey",value:{"ctrl+shift+t":function(){t.go("/transport")}},expression:"{'ctrl+shift+t': () => {go('/transport')}}"}],staticClass:"q-pa-xs"},[a("q-item-section",{staticClass:"flex-center",attrs:{side:""}},[a("q-icon",{attrs:{name:"fal fa-space-shuttle"}}),a("p",{staticClass:"menu-caption"},[a("span",{staticClass:"shortcut"},[t._v("T")]),t._v("ransport")])],1)],1)],1):t._e(),t.hasModuleAccess("lab")?a("router-link",{staticClass:"bg-black",attrs:{to:"/lab"}},[a("q-item",{directives:[{name:"hotkey",rawName:"v-hotkey",value:{"ctrl+shift+l":function(){t.go("/lab")}},expression:"{'ctrl+shift+l': () => {go('/lab')}}"}],staticClass:"q-pa-xs"},[a("q-item-section",{staticClass:"flex-center",attrs:{side:""}},[a("q-icon",{attrs:{name:"fal fa-flask"}}),a("p",{staticClass:"menu-caption"},[a("span",{staticClass:"shortcut"},[t._v("L")]),t._v("ab")])],1)],1)],1):t._e(),t.hasModuleAccess("admin")?a("router-link",{staticClass:"bg-black",attrs:{to:"/admin"}},[a("q-item",[a("q-item-section",{staticClass:"flex-center",attrs:{side:""}},[a("q-icon",{attrs:{name:"fal fa-wrench"}}),a("p",{staticClass:"menu-caption"},[a("span",{staticClass:"shortcut"},[t._v("A")]),t._v("dmin")])],1)],1)],1):t._e(),t.hasModuleAccess("academic")?a("router-link",{staticClass:"bg-black",attrs:{to:"/academic"}},[a("q-item",[a("q-item-section",{staticClass:"flex-center",attrs:{side:""}},[a("q-icon",{attrs:{name:"fal fa-smile"}}),a("p",{staticClass:"menu-caption"},[a("span",{staticClass:"shortcut"},[t._v("A")]),t._v("cademic")])],1)],1)],1):t._e()],1)],1),a("q-page-container",{staticClass:"no-scroll"},[a("router-view")],1)],1)},i=[],c=(a("e125"),a("4823"),a("2e73"),a("dde3"),a("76d0"),a("0c1f"),a("8e9e")),r=a.n(c),o=a("c569"),n=a("9ce4");function l(t,s){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var e=Object.getOwnPropertySymbols(t);s&&(e=e.filter(function(s){return Object.getOwnPropertyDescriptor(t,s).enumerable})),a.push.apply(a,e)}return a}function u(t){for(var s=1;s<arguments.length;s++){var a=null!=arguments[s]?arguments[s]:{};s%2?l(a,!0).forEach(function(s){r()(t,s,a[s])}):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):l(a).forEach(function(s){Object.defineProperty(t,s,Object.getOwnPropertyDescriptor(a,s))})}return t}var p={name:"LayoutDefault",data:function(){return{leftDrawerOpen:this.$q.platform.is.desktop,miniModeOn:!0,hideText:!0,router:this.$router,breakpoint:1}},methods:{openURL:o["a"],hasModuleAccess:function(t){return this.getModuleAccess(t)},hideTextProcess:function(){this.hideText=!this.hideText},go:function(t){this.$router.push(t)}},computed:u({},Object(n["e"])("user",["isDark","isLight"]),{},Object(n["c"])("user",["permissions","getModuleAccess","getModuleColor"]),{},Object(n["c"])("sockets",["isConnected"]),{keymap:function(){return{"ctrl+1":this.toggle,enter:{keydown:this.hide,keyup:this.show}}}}),created:function(){}},m=p,d=(a("7f67"),a("2be6")),f=Object(d["a"])(m,e,i,!1,null,null,null);s["default"]=f.exports},"7f67":function(t,s,a){"use strict";var e=a("2962"),i=a.n(e);i.a},c569:function(t,s,a){"use strict";var e=a("5094"),i=a("9869");s["a"]=function(t,s){var a=window.open;if(!0===e["a"].is.cordova){if(void 0!==cordova&&void 0!==cordova.InAppBrowser&&void 0!==cordova.InAppBrowser.open)a=cordova.InAppBrowser.open;else if(void 0!==navigator&&void 0!==navigator.app)return navigator.app.loadUrl(t,{openExternal:!0})}else if(void 0!==i["a"].prototype.$q.electron)return i["a"].prototype.$q.electron.shell.openExternal(t);var c=a(t,"_blank");if(c)return c.focus(),c;s&&s()}}}]);