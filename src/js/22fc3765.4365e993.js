(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["22fc3765"],{"08e9":function(e,t,n){"use strict";var a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("q-page",{staticClass:"no-scroll toolbar-page"},[n("q-toolbar",{class:{"text-white bg-toolbar":e.isDark,"text-black bg-white-3":e.isLight},attrs:{dense:"",shrink:"",classx:"text-white shadow-2 rounded-borders narrowx justify"}},[n("q-tabs",{staticClass:"tbp-tabs",attrs:{dense:"",shrink:"","active-color":e.isLight?"#011b48":"primary"},model:{value:e.selectedTab,callback:function(t){e.selectedTab=t},expression:"selectedTab"}},e._l(e.elements,(function(t){return n("div",{key:t.name},[t.menu?e._e():n("q-tab",{attrs:{label:t.label,name:t.name,icon:t.icon}}),t.menu?n("q-btn",{attrs:{flat:"",size:"sm",label:t.label,icon:t.icon?t.icon:"fal fa-caret-down","text-color":e.isDark?"white":"primary"}},[n("q-menu",{ref:"settingsPopover",refInFor:!0,attrs:{"content-class":"bg-grey-9 text-white","auto-close":""}},[n("q-list",{attrs:{"item-separator":"",link:"","content-class":"bg-primary"}},e._l(t.menu,(function(t){return n("q-item",{key:t.name,attrs:{clickable:""},nativeOn:{click:function(n){return e.clickMenu(t)}}},[n("q-item-section",{attrs:{avatar:"",left:"",dark:""}},[n("q-icon",{attrs:{size:"20px",name:t.icon}})],1),n("q-item-section",[n("q-item-label",[e._v(e._s(t.label))])],1)],1)})),1)],1)],1):e._e()],1)})),0),n("q-space"),e._t("side")],2),n("q-tab-panels",{model:{value:e.selectedTab,callback:function(t){e.selectedTab=t},expression:"selectedTab"}},e._l(e.tabPanels,(function(t){return n("q-tab-panel",{key:t.name,attrs:{name:t.name}},[n(t.component,{tag:"component",on:{close:e.close}})],1)})),1)],1)},l=[],i=(n("e125"),n("4823"),n("2e73"),n("dde3"),n("76d0"),n("0c1f"),n("c880"),n("8e9e")),r=n.n(i),s=n("9ce4");function c(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,a)}return n}function o(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?c(n,!0).forEach((function(t){r()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):c(n).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var u={name:"ComponentPageToolbar",props:{default:null,elements:null},watch:{selectedTab:function(){console.log(":",this.selectedTab)}},data:function(){return{selectedTab:null}},computed:o({},Object(s["e"])("user",["isDark","isLight"]),{tabPanels:function(){var e=[];return this.elements.forEach((function(t){t.menu?t.menu.forEach((function(t){e.push({name:t.name,component:t.component})})):e.push({name:t.name,component:t.component})})),e}}),methods:{close:function(){this.selectedTab=this.default},clickMenu:function(e){e.name&&(this.selectedTab=e.name),e.event&&this.$emit(e.event)}},created:function(){this.selectedTab=this.default}},m=u,f=(n("b0d4"),n("2be6")),b=Object(f["a"])(m,a,l,!1,null,null,null);t["a"]=b.exports},"0ae4":function(e,t,n){"use strict";n.r(t);var a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("toolbar-page",{attrs:{elements:e.elements}})},l=[],i=n("08e9"),r=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("crud",{ref:"crud",attrs:{data:e.data,api:e.api,columns:e.columns,search:"",sortBy:"lastName"}})],1)},s=[],c=n("d612"),o=n("4778"),u={getPrizes:function(e,t,n){o["a"].get("/academic/prizes").then((function(t){e(t.data)})).catch((function(e){console.log(e)}))}},m={name:"ComponentJanePrizes",data:function(){return{api:{get:u.getPrizes},columns:[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"lastName",label:"Last Name",field:"lastName",type:"string",align:"left",filter:!0,sortable:!0},{name:"firstName",label:"First Name",field:"firstName",type:"string",align:"left"},{name:"gender",label:"M/F",field:"gender",type:"string",align:"left"},{name:"names",label:"Parents",field:"txtLabelSalutation",type:"string",align:"left"},{name:"email1",label:"Email 1",field:"txtEmail1",type:"string",align:"left"},{name:"email2",label:"Email 2",field:"txtEmail2",type:"string",align:"left"},{name:"txtName",label:"Prize",field:"txtName",type:"string",align:"left"},{name:"txtDescription",label:"Description",field:"txtDescription",type:"string",align:"left"}],showForm:!0}},computed:{},components:{Crud:c["a"]},created:function(){}},f=m,b=(n("9406"),n("2be6")),p=Object(b["a"])(f,r,s,!1,null,"1de5c6c1",null),d=p.exports,g={name:"PageLabCrud",data:function(){return{elements:[{name:"prizes",label:"prizes",component:d,shortcut:"b"}]}},components:{toolbarPage:i["a"]}},h=g,y=Object(b["a"])(h,a,l,!1,null,null,null);t["default"]=y.exports},"796a":function(e,t,n){},9406:function(e,t,n){"use strict";var a=n("796a"),l=n.n(a);l.a},b0d4:function(e,t,n){"use strict";var a=n("b3f7"),l=n.n(a);l.a},b3f7:function(e,t,n){}}]);