(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["775b9ef5"],{"08e9":function(e,t,a){"use strict";var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("q-page",{staticClass:"no-scroll toolbar-page"},[a("q-toolbar",{class:{"text-white bg-toolbar":e.isDark,"text-black bg-white-3":e.isLight},attrs:{dense:"",shrink:"",classx:"text-white shadow-2 rounded-borders narrowx justify"}},[e._t("before"),a("q-tabs",{staticClass:"tbp-tabs",attrs:{dense:"",shrink:"","active-color":e.isLight?"#011b48":"primary"},model:{value:e.selectedTab,callback:function(t){e.selectedTab=t},expression:"selectedTab"}},e._l(e.elements,(function(t){return a("div",{key:t.name},[t.menu?e._e():a("q-tab",{attrs:{label:t.label,name:t.name,icon:t.icon}}),t.menu?a("q-btn",{attrs:{flat:"",size:"sm",label:t.label,icon:t.icon?t.icon:"fal fa-caret-down","text-color":e.isDark?"white":"primary"}},[a("q-menu",{ref:"settingsPopover",refInFor:!0,attrs:{"content-class":"bg-grey-9 text-white","auto-close":""}},[a("q-list",{attrs:{"item-separator":"",link:"","content-class":"bg-primary"}},e._l(t.menu,(function(t){return a("q-item",{key:t.name,attrs:{clickable:""},nativeOn:{click:function(a){return e.clickMenu(t)}}},[a("q-item-section",{attrs:{avatar:"",left:"",dark:""}},[a("q-icon",{attrs:{size:"20px",name:t.icon}})],1),a("q-item-section",[a("q-item-label",[e._v(e._s(t.label))])],1)],1)})),1)],1)],1):e._e()],1)})),0),a("q-space"),e._t("side"),e._t("after")],2),a("q-tab-panels",{model:{value:e.selectedTab,callback:function(t){e.selectedTab=t},expression:"selectedTab"}},e._l(e.tabPanels,(function(t){return a("q-tab-panel",{key:t.name,attrs:{name:t.name}},[a(t.component,{tag:"component",on:{close:e.close}})],1)})),1)],1)},s=[],r=(a("e125"),a("4823"),a("2e73"),a("dde3"),a("76d0"),a("0c1f"),a("c880"),a("8e9e")),i=a.n(r),o=a("9ce4");function l(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,n)}return a}function c(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?l(a,!0).forEach((function(t){i()(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):l(a).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}var d={name:"ComponentPageToolbar",props:{default:null,elements:null},watch:{selectedTab:function(){console.log(":",this.selectedTab)}},data:function(){return{selectedTab:null}},computed:c({},Object(o["e"])("user",["isDark","isLight"]),{tabPanels:function(){var e=[];return this.elements.forEach((function(t){t.menu?t.menu.forEach((function(t){e.push({name:t.name,component:t.component})})):e.push({name:t.name,component:t.component})})),e}}),methods:{close:function(){this.selectedTab=this.default},clickMenu:function(e){e.name&&(this.selectedTab=e.name),e.event&&this.$emit(e.event)}},created:function(){this.selectedTab=this.default}},u=d,m=(a("b0d4"),a("2be6")),p=Object(m["a"])(u,n,s,!1,null,null,null);t["a"]=p.exports},"48bf":function(e,t,a){},"5eb9":function(e,t,a){},"854d":function(e,t,a){"use strict";a.r(t);var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("toolbar-page",{attrs:{elements:e.elements}})},s=[],r=a("08e9"),i=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("div",{staticClass:"row fit"},[a("div",{staticClass:"flex-center q-mr-md"},[a("q-btn",{staticClass:"q-mt-md q-mx-sm",attrs:{roundx:"",color:"grey-9",size:"sm",icon:"fal fa-retweet-alt"},on:{click:e.getAttendance}}),a("q-btn",{staticClass:"q-mt-md q-ml-sm",attrs:{roundx:"",color:"grey-9",size:"sm",icon:"fal fa-paper-plane"},on:{click:e.sendHouseEmails}})],1),a("div",{staticClass:"col-4 q-mr-lg"},[a("q-input",{attrs:{filled:"",maskx:"DD-MM-YYYY",dark:""},scopedSlots:e._u([{key:"append",fn:function(){return[a("q-icon",{staticClass:"cursor-pointer",attrs:{name:"event"}},[a("q-popup-proxy",{ref:"qDateProxy",attrs:{"transition-show":"scale","transition-hide":"scale"}},[a("q-date",{attrs:{options:e.dateOptions,minimal:"",dark:"",mask:"DD-MM-YYYY","first-day-of-week":"1"},on:{input:function(){return e.$refs.qDateProxy.hide()}},model:{value:e.dateString,callback:function(t){e.dateString=t},expression:"dateString"}})],1)],1)]},proxy:!0}]),model:{value:e.dateString,callback:function(t){e.dateString=t},expression:"dateString"}})],1),a("div",{staticClass:"col"},[a("q-card",{staticClass:"q-mt-md bg-toolbar"},[a("q-tabs",{staticClass:"text-grey",attrs:{dense:"",dark:"","active-color":"primary","indicator-color":"primary",align:"justify","narrow-indicator":""},model:{value:e.tab,callback:function(t){e.tab=t},expression:"tab"}},[a("q-tab",{attrs:{name:"summary",label:"Summary"}}),a("q-tab",{attrs:{name:"houses",label:"Houses"}}),a("q-tab",{attrs:{name:"privs",label:"Privs"}})],1)],1)],1)]),a("div",{staticClass:"row fit"},[e._v("f\n      "),a("q-tab-panels",{staticClass:"fit ",attrs:{animated:""},model:{value:e.tab,callback:function(t){e.tab=t},expression:"tab"}},[a("q-tab-panel",{attrs:{name:"summary"}},[a("crud",{ref:"crud",staticStyle:{max:"height:80vh"},attrs:{dataOverride:e.housesSummary,columns:e.houseSummaryColumns,sortBy:"id"}})],1),a("q-tab-panel",{staticClass:"col",attrs:{name:"houses"}},[a("div",{staticClass:"row fit full-width bg-toolbar"},[a("q-select",{staticClass:"no-margin text-secondary q-my-sm",staticStyle:{"min-width":"250px","max-width":"500px","border-radius":"5px"},attrs:{outlined:"",options:e.houses,dense:"","options-dense":"","options-dark":"","emit-value":"",dark:"",inverted:"",placeholder:"House",filter:"","filter-placeholder":"search"},model:{value:e.house,callback:function(t){e.house=t},expression:"house"}}),a("q-tabs",{staticClass:"text-grey",attrs:{dense:"",dark:"","active-color":"primary","indicator-color":"primary",align:"justify","narrow-indicator":""},model:{value:e.housetab,callback:function(t){e.housetab=t},expression:"housetab"}},[a("q-tab",{attrs:{name:"missed",label:"Missed"}},[e.data?a("q-badge",{attrs:{color:"red",floating:""}},[e._v(e._s(e.currentHouse.missed.length))]):e._e()],1),a("q-tab",{attrs:{name:"attended",label:"Attended"}},[e.data?a("q-badge",{attrs:{color:"primary",floating:""}},[e._v(e._s(e.currentHouse.attended.length))]):e._e()],1),a("q-tab",{attrs:{name:"priv",label:"Privs"}},[e.data?a("q-badge",{attrs:{color:"primary",floating:""}},[e._v(e._s(e.currentHouse.priv.length))]):e._e()],1),a("q-tab",{attrs:{name:"history",label:"history",disabled:""}})],1)],1),a("div",{staticClass:"row"},[a("q-tab-panels",{staticClass:"fit",attrs:{animated:""},model:{value:e.housetab,callback:function(t){e.housetab=t},expression:"housetab"}},[a("q-tab-panel",{attrs:{name:"missed"}},[a("crud",{ref:"crud",staticStyle:{max:"height:80vh"},attrs:{dataOverride:e.currentHouse.missed,columns:e.missedColumns,sortBy:"name",search:"",rowsPerPage:"1000"}})],1),a("q-tab-panel",{attrs:{name:"attended"}},[a("crud",{ref:"crud",staticStyle:{max:"height:80vh"},attrs:{dataOverride:e.currentHouse.attended,columns:e.attendedColumns,sortBy:"name",search:"",rowsPerPage:"1000"}})],1),a("q-tab-panel",{attrs:{name:"priv"}},[a("crud",{ref:"crud",staticStyle:{max:"height:80vh"},attrs:{dataOverride:e.currentHouse.priv,columns:e.attendedColumns,sortBy:"name",search:"",rowsPerPage:"1000"}})],1),a("q-tab-panel",{attrs:{name:"history"}})],1)],1)])],1)],1),a("q-dialog",{attrs:{"content-class":"bg-dark"},model:{value:e.emailPanel,callback:function(t){e.emailPanel=t},expression:"emailPanel"}},[a("q-card",{staticClass:"bg-grey-2"},[a("q-toolbar",[a("q-avatar",[a("q-icon",{attrs:{name:"fal fa-eye"}})],1),a("q-toolbar-title",[a("span",{staticClass:"text-weight-bold"},[e._v("Chapel")]),e._v(" House Emails")]),a("q-btn",{directives:[{name:"close-popup",rawName:"v-close-popup"}],attrs:{flat:"",round:"",dense:"",icon:"close"}})],1),a("q-card-section",[e._v("\n          Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum repellendus sit voluptate voluptas eveniet porro. Rerum blanditiis perferendis totam, ea at omnis vel numquam exercitationem aut, natus minima, porro labore.\n        ")])],1)],1)],1)},o=[],l=(a("e125"),a("4823"),a("2e73"),a("dde3"),a("76d0"),a("0c1f"),a("288e"),a("c880"),a("8e9e")),c=a.n(l),d=a("9ce4"),u=a("d612"),m=a("4778"),p={getAttendance:function(e,t,a){m["a"].get("/smt/watch/chapel/attendance/"+a).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},postEmail:function(e,t,a){m["a"].post("/smt/watch/chapel/attendance/"+a).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))}},f=a("c286");function b(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,n)}return a}function h(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?b(a,!0).forEach((function(t){c()(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):b(a).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}var g={name:"PageResults",props:{},data:function(){return{dateString:"",emailPanel:!1,tab:"summary",housetab:"missed",house:"B1",houses:[],data:null,missedColumns:[{name:"id",label:"id",field:"ada_id",type:"string",align:"left",hidden:!0},{name:"name",label:"Name",field:"name",type:"string",align:"left"}],attendedColumns:[{name:"id",label:"id",field:"ada_id",type:"string",align:"left",hidden:!0},{name:"name",label:"Name",field:"name",type:"string",align:"left"}],houseSummaryColumns:[{name:"id",label:"House",field:"id",type:"string",align:"left"},{name:"attended",label:"Attended",field:"attended",type:"string",align:"left"},{name:"privs",label:"Privs",field:"privs",type:"string",align:"left"},{name:"early",label:"Early",field:"early",type:"string",align:"left"},{name:"late",label:"Late",field:"late",type:"string",align:"left"},{name:"talk",label:"Talk",field:"talk",type:"string",align:"left"},{name:"missed",label:"Missed",field:"missed",type:"string",align:"left"}]}},methods:h({},Object(d["b"])("sockets",["clearConsoleLog"]),{getAttendance:function(){this.$q.loading.show(),p.getAttendance(this.process,null,this.dateString)},process:function(e){this.data=e,this.houses=e.allHouses.map((function(e){return{label:e.name,value:e.name}})),this.$q.loading.hide()},dateOptions:function(e){var t=f["b"].formatDate(e,"ddd");return"Sun"===t},sendHouseEmails:function(){this.emailPanel=!0}}),computed:{currentHouse:function(){var e=this;if(!this.data)return{attended:[],missed:[]};var t=this.data.allHouses.find((function(t){return t.name===e.house}));return t},housesSummary:function(){return this.data?this.data.allHouses.map((function(e){return{id:e.name,attended:e.attended.length,privs:e.priv.length,early:e.sessions.early.length,late:e.sessions.late.length,talk:e.sessions.talk.length,missed:e.missed.length,priv:e.priv}})):[]}},watch:{dateString:function(){this.getAttendance()}},components:{Crud:u["a"]},created:function(){var e=Date.now(),t=f["b"].formatDate(e,"d"),a=f["b"].subtractFromDate(e,{days:t}),n=f["b"].formatDate(a,"DD-MM-YYYY");this.dateString=n,this.getAttendance()}},v=g,y=(a("afe2"),a("2be6")),q=Object(y["a"])(v,i,o,!1,null,"15c3981e",null),w=q.exports,O=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("div",{staticClass:"row fit"},[a("div",{staticClass:" flex-center"},[a("q-btn",{staticClass:"q-mt-md q-mx-lg",attrs:{roundx:"",color:"grey-9",size:"sm",icon:"fal fa-retweet-alt"},on:{click:e.getPrivs}})],1),a("div",{staticClass:"col-4 q-mr-lg"},[a("q-input",{attrs:{filled:"",maskx:"DD-MM-YYYY",dark:""},scopedSlots:e._u([{key:"append",fn:function(){return[a("q-icon",{staticClass:"cursor-pointer",attrs:{name:"event"}},[a("q-popup-proxy",{ref:"qDateProxy",attrs:{"transition-show":"scale","transition-hide":"scale"}},[a("q-date",{attrs:{minimal:"",dark:"",mask:"DD-MM-YYYY",options:e.dateOptions,"first-day-of-week":"1"},on:{input:function(){return e.$refs.qDateProxy.hide()}},model:{value:e.privDate,callback:function(t){e.privDate=t},expression:"privDate"}})],1)],1)]},proxy:!0}]),model:{value:e.privDate,callback:function(t){e.privDate=t},expression:"privDate"}})],1)]),a("div",{staticClass:"row fit"},[a("div",{staticClass:"col-6"},[a("h1",[e._v("Total: "+e._s(e.data.count))]),a("crud",{ref:"crud",staticStyle:{max:"height:80vh"},attrs:{dataOverride:e.houses,columns:e.columns,sortBy:"id"}})],1)])])},k=[],P={getPrivs:function(e,t,a){m["a"].get("/smt/watch/privs/"+a).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))}};function x(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,n)}return a}function D(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?x(a,!0).forEach((function(t){c()(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):x(a).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}var C={name:"PageResults",props:{},data:function(){return{privDate:"",houses:[],data:null,columns:[{name:"id",label:"id",field:"ada_id",type:"string",align:"left",hidden:!0},{name:"name",label:"Name",field:"name",type:"string",align:"left"},{name:"count",label:"Count",field:"count",type:"number",align:"right"}]}},methods:D({},Object(d["b"])("sockets",["clearConsoleLog"]),{getPrivs:function(){this.$q.loading.show(),P.getPrivs(this.process,null,this.privDate)},process:function(e){this.data=e,this.houses=e.houses,this.$q.loading.hide()},dateOptions:function(e){var t=f["b"].formatDate(e,"ddd");return"Sat"===t}}),computed:{},watch:{privDate:function(){this.getPrivs()}},components:{Crud:u["a"]},created:function(){var e=Date.now(),t=6-parseInt(f["b"].formatDate(e,"d")),a=f["b"].addToDate(e,{days:t}),n=f["b"].formatDate(a,"DD-MM-YYYY");this.privDate=n,this.getPrivs()}},_=C,j=(a("cfaa"),Object(y["a"])(_,O,k,!1,null,"2a146e14",null)),S=j.exports,Y={name:"PageSMTWatch",data:function(){return{elements:[{name:"chapel",label:"chapel",component:w,shortcut:"c"},{name:"privs",label:"privs",component:S,shortcut:"p"}]}},components:{toolbarPage:r["a"]}},E=Y,M=Object(y["a"])(E,n,s,!1,null,null,null);t["default"]=M.exports},afe2:function(e,t,a){"use strict";var n=a("5eb9"),s=a.n(n);s.a},b0d4:function(e,t,a){"use strict";var n=a("b3f7"),s=a.n(n);s.a},b3f7:function(e,t,a){},cfaa:function(e,t,a){"use strict";var n=a("48bf"),s=a.n(n);s.a}}]);