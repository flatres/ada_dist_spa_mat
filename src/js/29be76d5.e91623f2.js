(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["29be76d5"],{"08e9":function(e,t,a){"use strict";var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("q-page",{staticClass:"no-scroll"},[a("q-toolbar",{staticClass:"bg-toolbar text-white shadow-2 rounded-borders narrowx justify",attrs:{dense:"",shrink:""}},[a("q-tabs",{attrs:{dense:"",shrink:"","indicator-color":"primary"},model:{value:e.selectedTab,callback:function(t){e.selectedTab=t},expression:"selectedTab"}},e._l(e.elements,function(t){return a("div",{key:t.name},[t.menu?e._e():a("q-tab",{attrs:{label:t.label,name:t.name}}),t.menu?a("q-btn",{directives:[{name:"hotkey",rawName:"v-hotkey",value:{"shift+a":function(){e.selectedTab="bookings"}},expression:"{'shift+a': () => { selectedTab = 'bookings' }}"}],attrs:{flat:"",label:"Admin",icon:"fal fa-caret-down"},nativeOn:{click:function(a){a.stopPropagation(),e.selectedTab=t.name}}},[a("q-menu",{ref:"settingsPopover",refInFor:!0,attrs:{"content-class":"bg-dark text-white","auto-close":""}},[a("q-list",{attrs:{"item-separator":"",link:"","content-class":"bg-primary"}},e._l(t.menu,function(t){return a("q-item",{key:t.name,attrs:{clickable:""},nativeOn:{click:function(a){e.selectedTab=t.name}}},[a("q-item-section",{attrs:{avatar:"",left:"",dark:""}},[a("q-icon",{attrs:{name:t.icon+" fa-sm"}})],1),a("q-item-section",[a("q-item-label",[e._v(e._s(t.label))])],1)],1)}),1)],1)],1):e._e()],1)}),0),a("q-space"),e._t("side")],2),a("q-tab-panels",{model:{value:e.selectedTab,callback:function(t){e.selectedTab=t},expression:"selectedTab"}},e._l(e.tabPanels,function(t){return a("q-tab-panel",{key:t.name,attrs:{name:t.name}},[a(t.component,{tag:"component",on:{close:function(t){e.selectedTab=null}}})],1)}),1)],1)},s=[],o=(a("c880"),a("2e73"),{name:"ComponentPageToolbar",props:{default:null,elements:null},watch:{selectedTab:function(){console.log(":",this.selectedTab)}},data:function(){return{selectedTab:null}},computed:{tabPanels:function(){var e=[];return this.elements.forEach(function(t){t.menu?t.menu.forEach(function(t){e.push({name:t.name,component:t.component})}):e.push({name:t.name,component:t.component})}),e}},created:function(){this.selectedTab=this.default}}),l=o,c=a("2be6"),r=Object(c["a"])(l,n,s,!1,null,null,null);t["a"]=r.exports},"8d47":function(e,t,a){},ea66:function(e,t,a){"use strict";a.r(t);var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("toolbar-page",{attrs:{elements:e.elements}})},s=[],o=a("08e9"),l=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"row"},[a("div",{staticClass:"col-4"},[a("crud",{ref:"crud",staticStyle:{max:"height:80vh"},attrs:{api:e.areasApi,columns:e.areaColumns,sortBy:"name",search:"",rowsPerPage:"15"},on:{clickedRow:e.clickedArea}})],1),a("div",{staticClass:"col-4"}),a("div",{staticClass:"col-4"})])},c=[],r=a("d612"),i=a("4778"),u={getAreas:function(e,t,a){i["a"].get("/location/system/areas ").then(function(t){e(t.data)}).catch(function(e){console.log(e)})},getArea:function(e,t,a){i["a"].get("/location/system/areas/"+a).then(function(t){e(t.data)}).catch(function(e){console.log(e)})}},m={name:"ComponentLocationSystemAreas",data:function(){return{areasApi:{get:u.getAreas},areaColumns:[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"Name",label:"Name",field:"name",type:"string",align:"left"}],areaData:[]}},methods:{clickedArea:function(e){var t=this;u.getArea(function(e){t.areaData=e},null,e),console.log(e)}},computed:{},components:{Crud:r["a"]},created:function(){}},d=m,f=(a("eed4"),a("2be6")),b=Object(f["a"])(d,l,c,!1,null,"dd57f0f2",null),p=b.exports,h={name:"PageLocSystem",data:function(){return{elements:[{name:"areas",label:"Areas",component:p,shortcut:"b"}]}},components:{toolbarPage:o["a"]}},g=h,v=Object(f["a"])(g,n,s,!1,null,null,null);t["default"]=v.exports},eed4:function(e,t,a){"use strict";var n=a("8d47"),s=a.n(n);s.a}}]);