(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["42fe8da8"],{"08e9":function(e,t,n){"use strict";var a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("q-page",{staticClass:"no-scroll toolbar-page"},[n("q-toolbar",{class:{"text-white bg-toolbar":e.isDark,"text-black bg-white-3":e.isLight},attrs:{dense:"",shrink:"",classx:"text-white shadow-2 rounded-borders narrowx justify"}},[e._t("before"),n("q-tabs",{staticClass:"tbp-tabs",attrs:{dense:"",shrink:"","active-color":e.isLight?"#011b48":"primary"},model:{value:e.selectedTab,callback:function(t){e.selectedTab=t},expression:"selectedTab"}},e._l(e.elements,(function(t){return n("div",{key:t.name},[t.menu?e._e():n("q-tab",{attrs:{label:t.label,name:t.name,icon:t.icon}},[t.count>0?n("q-badge",{attrs:{color:"lime-13","text-color":"black",floating:""}},[e._v(e._s(t.count))]):e._e(),t.tooltip?n("q-tooltip",{attrs:{"transition-show":"scale","transition-hide":"scale"}},[e._v("\n             "+e._s(t.tooltip)+"\n           ")]):e._e()],1),t.menu?n("q-btn",{attrs:{flat:"",size:"sm",label:t.label,icon:t.icon?t.icon:"fal fa-caret-down","text-color":e.isDark?"white":"primary"}},[n("q-menu",{ref:"settingsPopover",refInFor:!0,attrs:{"content-class":"bg-grey-9 text-white","auto-close":""}},[n("q-list",{attrs:{"item-separator":"",link:"","content-class":"bg-primary"}},e._l(t.menu,(function(t){return n("q-item",{key:t.name,attrs:{clickable:""},nativeOn:{click:function(n){return e.clickMenu(t)}}},[n("q-item-section",{attrs:{avatar:"",left:"",dark:""}},[n("q-icon",{attrs:{size:"20px",name:t.icon}})],1),n("q-item-section",[n("q-item-label",[e._v(e._s(t.label))])],1)],1)})),1)],1)],1):e._e()],1)})),0),n("q-space"),e._t("side"),e._t("after")],2),n("q-tab-panels",{model:{value:e.selectedTab,callback:function(t){e.selectedTab=t},expression:"selectedTab"}},e._l(e.tabPanels,(function(t){return n("q-tab-panel",{key:t.name,attrs:{name:t.name}},[n(t.component,{tag:"component",on:{close:e.close}})],1)})),1)],1)},o=[],c=(n("e125"),n("4823"),n("2e73"),n("dde3"),n("76d0"),n("0c1f"),n("c880"),n("8e9e")),s=n.n(c),r=n("9ce4");function l(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,a)}return n}function i(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?l(Object(n),!0).forEach((function(t){s()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):l(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var u={name:"ComponentPageToolbar",props:{default:null,elements:null},watch:{selectedTab:function(){console.log(":",this.selectedTab)}},data:function(){return{selectedTab:null}},computed:i({},Object(r["e"])("user",["isDark","isLight"]),{tabPanels:function(){var e=[];return this.elements.forEach((function(t){t.menu?t.menu.forEach((function(t){e.push({name:t.name,component:t.component})})):e.push({name:t.name,component:t.component})})),e}}),methods:{close:function(){this.selectedTab=this.default},clickMenu:function(e){e.name&&(this.selectedTab=e.name),e.event&&this.$emit(e.event)}},created:function(){this.selectedTab=this.default}},b=u,f=(n("b0d4"),n("2be6")),m=Object(f["a"])(b,a,o,!1,null,null,null);t["a"]=m.exports},"1d26":function(e,t,n){"use strict";var a=n("367b"),o=n.n(a);o.a},"367b":function(e,t,n){},b0d4:function(e,t,n){"use strict";var a=n("b3f7"),o=n.n(a);o.a},b3f7:function(e,t,n){},c739:function(e,t,n){"use strict";n.r(t);var a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("toolbar-page",{attrs:{elements:e.elements,default:"calendar"},scopedSlots:e._u([{key:"side",fn:function(){},proxy:!0}])})},o=[],c=n("08e9"),s=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("q-page",{},[n("div",{staticClass:"row"},[n("q-btn",{staticClass:"no-shadow",attrs:{color:"primary",outline:"",size:"sm",icon:"fal fa-sync-alt"},on:{click:e.fetch}})],1),n("div",{staticClass:"row fit"})])},r=[],l=n("4778"),i={getAlmanac:function(e,t,n){l["a"].get("/home/almanac").then((function(t){e(t.data)})).catch((function(e){console.log(e)}))}},u={name:"Home.Almanac.Calandar",props:{},data:function(){return{events:[]}},methods:{process:function(e){this.events=e},fetch:function(){i.getAlmanac(this.process)}},computed:{},components:{},created:function(){this.fetch()}},b=u,f=(n("1d26"),n("2be6")),m=Object(f["a"])(b,s,r,!1,null,"69101ed8",null),p=m.exports,d={name:"PageHomeAlmanac",data:function(){return{elements:[{name:"calendar",label:"calendar",component:p,shortcut:"b"}]}},components:{toolbarPage:c["a"]},methods:{}},h=d,g=Object(f["a"])(h,a,o,!1,null,null,null);t["default"]=g.exports}}]);