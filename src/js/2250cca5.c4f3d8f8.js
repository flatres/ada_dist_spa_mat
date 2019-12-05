(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["2250cca5"],{"08e9":function(t,e,n){"use strict";var s=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("q-page",{staticClass:"no-scroll toolbar-page"},[n("q-toolbar",{class:{"text-white bg-toolbar":t.isDark,"text-black bg-white-3":t.isLight},attrs:{dense:"",shrink:"",classx:"text-white shadow-2 rounded-borders narrowx justify"}},[t._t("before"),n("q-tabs",{staticClass:"tbp-tabs",attrs:{dense:"",shrink:"","active-color":t.isLight?"#011b48":"primary"},model:{value:t.selectedTab,callback:function(e){t.selectedTab=e},expression:"selectedTab"}},t._l(t.elements,(function(e){return n("div",{key:e.name},[e.menu?t._e():n("q-tab",{attrs:{label:e.label,name:e.name,icon:e.icon}},[e.count>0?n("q-badge",{attrs:{color:"lime-13","text-color":"black",floating:""}},[t._v(t._s(e.count))]):t._e(),e.tooltip?n("q-tooltip",{attrs:{"transition-show":"scale","transition-hide":"scale"}},[t._v("\n             "+t._s(e.tooltip)+"\n           ")]):t._e()],1),e.menu?n("q-btn",{attrs:{flat:"",size:"sm",label:e.label,icon:e.icon?e.icon:"fal fa-caret-down","text-color":t.isDark?"white":"primary"}},[n("q-menu",{ref:"settingsPopover",refInFor:!0,attrs:{"content-class":"bg-grey-9 text-white","auto-close":""}},[n("q-list",{attrs:{"item-separator":"",link:"","content-class":"bg-primary"}},t._l(e.menu,(function(e){return n("q-item",{key:e.name,attrs:{clickable:""},nativeOn:{click:function(n){return t.clickMenu(e)}}},[n("q-item-section",{attrs:{avatar:"",left:"",dark:""}},[n("q-icon",{attrs:{size:"20px",name:e.icon}})],1),n("q-item-section",[n("q-item-label",[t._v(t._s(e.label))])],1)],1)})),1)],1)],1):t._e()],1)})),0),n("q-space"),t._t("side"),t._t("after")],2),n("q-tab-panels",{model:{value:t.selectedTab,callback:function(e){t.selectedTab=e},expression:"selectedTab"}},t._l(t.tabPanels,(function(e){return n("q-tab-panel",{key:e.name,attrs:{name:e.name}},[n(e.component,{tag:"component",on:{close:t.close}})],1)})),1)],1)},a=[],l=(n("e125"),n("4823"),n("2e73"),n("dde3"),n("76d0"),n("0c1f"),n("c880"),n("8e9e")),o=n.n(l),r=n("9ce4");function i(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,s)}return n}function c(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?i(Object(n),!0).forEach((function(e){o()(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):i(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}var u={name:"ComponentPageToolbar",props:{default:null,elements:null},watch:{selectedTab:function(){console.log(":",this.selectedTab)}},data:function(){return{selectedTab:null}},computed:c({},Object(r["e"])("user",["isDark","isLight"]),{tabPanels:function(){var t=[];return this.elements.forEach((function(e){e.menu?e.menu.forEach((function(e){t.push({name:e.name,component:e.component})})):t.push({name:e.name,component:e.component})})),t}}),methods:{close:function(){this.selectedTab=this.default},clickMenu:function(t){t.name&&(this.selectedTab=t.name),t.event&&this.$emit(t.event)}},created:function(){this.selectedTab=this.default}},d=u,f=(n("b0d4"),n("2be6")),p=Object(f["a"])(d,s,a,!1,null,null,null);e["a"]=p.exports},"0cf2":function(t,e,n){},"48d9":function(t,e,n){"use strict";var s=n("d522"),a=n.n(s);a.a},8976:function(t,e,n){"use strict";n.r(e);var s=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("toolbar-page",{attrs:{elements:t.elements,default:"students"}})},a=[],l=n("08e9"),o=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("q-page",{},[n("div",{staticClass:"row"},[n("div",{staticClass:"col-8"},[n("h1",[t._v(" iSAMS Students ")]),n("crud",{ref:"crud",attrs:{dataOverride:t.misStudents,api:t.api,columns:t.columns,settings:t.settings,actions:t.actions,sortBy:"name",rowKey:"id",search:""}})],1),n("div",{staticClass:"col-4"},[n("div",{staticClass:"block border-primary q-ba-sm"},[n("div",{staticClass:"row"},[n("div",{attrs:{clsss:"col flex"}},[n("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"primary",dark:"",readonly:"",label:"# MIS Students"},model:{value:t.stats.misStudentCount,callback:function(e){t.$set(t.stats,"misStudentCount",e)},expression:"stats.misStudentCount"}})],1),n("div",{staticClass:"col"},[n("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"primary",dark:"",readonly:"",label:"# ADA Students"},model:{value:t.stats.adaStudentCount,callback:function(e){t.$set(t.stats,"adaStudentCount",e)},expression:"stats.adaStudentCount"}})],1)]),n("q-btn",{staticClass:"full-width q-mt-xl",attrs:{loading:t.loading,percentage:t.syncProgress,"dark-percentagex":"",outline:"",dark:"",color:"primary","icon-rightx":"fal fa-sync fa-xs"},on:{click:t.syncStudents}},[t._v("\n           Sync\n          "),n("span",{attrs:{slot:"loading"},slot:"loading"},[n("q-spinner-gears",{staticClass:"on-left"}),t._v("\n            Computing...\n          ")],1)]),n("console",{staticStyle:{"min-height":"300px"}}),t.syncIsComplete&&!t.loading?n("div",[n("h1",[t._v("Summary")]),n("div",{attrs:{clsss:"row"}},[n("div",{staticClass:"col"},[n("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"primary",dark:"",readonly:"",label:"New"},model:{value:t.results.new,callback:function(e){t.$set(t.results,"new",e)},expression:"results.new"}})],1)]),n("div",{staticClass:"row full-width"},[n("div",{staticClass:"col"},[n("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"primary",dark:"",readonly:"",label:"Updated"},model:{value:t.results.updated,callback:function(e){t.$set(t.results,"updated",e)},expression:"results.updated"}})],1)]),n("div",{staticClass:"row full-width"},[n("div",{staticClass:"col"},[n("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"primary",dark:"",readonly:"",label:"Disabled"},model:{value:t.results.disabled,callback:function(e){t.$set(t.results,"disabled",e)},expression:"results.disabled"}})],1)])]):t._e()],1)])])])},r=[],i=(n("e125"),n("4823"),n("2e73"),n("dde3"),n("76d0"),n("0c1f"),n("8e9e")),c=n.n(i),u=n("9ce4"),d=n("4778"),f={getMISStudents:function(t){d["a"].get("/admin/sync/misstudents").then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},postSyncStudents:function(t){d["a"].post("/admin/sync/students").then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getMISStaff:function(t){d["a"].get("/admin/sync/misstaff").then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},postSyncStaff:function(t){d["a"].post("/admin/sync/staff").then((function(e){t(e.data)})).catch((function(t){console.log(t)}))}},p=n("d612"),m=n("dd14");function b(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,s)}return n}function g(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?b(Object(n),!0).forEach((function(e){c()(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):b(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}var y={name:"ComponentAdminSyncStudentSync",props:{},data:function(){return{id:"0",stats:{misStudentCount:0,adaStudentCount:0},misStudentCount:"",misStudents:[],columns:[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"lastName",required:!0,label:"Name",align:"left",field:"txtSurname",sortable:!0},{name:"firstName",required:!0,label:"",align:"left",field:"txtForename",sortable:!0},{name:"gender",label:"M/F",field:"txtGender",align:"left",sortable:!0},{name:"house",label:"Hs",field:"txtBoardingHouse",align:"left",sortable:!0}],loading:!1,percentage:100,syncIsComplete:!1,results:[],channel:"lab.crud.basic"}},methods:g({},Object(u["b"])("sockets",["clearConsoleLog"]),{showMISStudents:function(t){this.misStudents=t.misStudents,this.stats=t.stats},syncStudents:function(){this.clearConsoleLog(),this.loading=!0,f.postSyncStudents(this.syncComplete)},syncComplete:function(t){this.results=t,this.loading=!1,this.syncIsComplete=!0,this.percentage=0}}),computed:g({},Object(u["c"])("sockets",["progress"]),{syncProgress:function(){return this.progress("Admin/Sync/Students")}}),components:{Crud:p["a"],Console:m["a"]},created:function(){f.getMISStudents(this.showMISStudents)}},h=y,v=(n("a0f5"),n("2be6")),S=Object(v["a"])(h,o,r,!1,null,"23c7d0fe",null),O=S.exports,C=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("q-page",{},[n("h1",[t._v("MIS Staff")]),n("div",{staticClass:"row"},[n("div",{staticClass:"col-8"},[n("crud",{ref:"crud",attrs:{dataOverride:t.misStaff,api:t.api,columns:t.columns,settings:t.settings,actions:t.actions,sortBy:"lastName",rowKey:"id",search:"",filterchips:""}})],1),n("div",{staticClass:"col-4"},[n("div",{staticClass:"block border-primary q-ba-sm"},[n("div",{staticClass:"row"},[n("div",{attrs:{clsss:"col flex"}},[n("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"primary",dark:"",readonly:"",label:"# MIS Staff"},model:{value:t.stats.misStaffCount,callback:function(e){t.$set(t.stats,"misStaffCount",e)},expression:"stats.misStaffCount"}})],1),n("div",{staticClass:"col"},[n("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"primary",dark:"",readonly:"",label:"# ADA Staff"},model:{value:t.stats.adaStaffCount,callback:function(e){t.$set(t.stats,"adaStaffCount",e)},expression:"stats.adaStaffCount"}})],1)]),n("q-btn",{staticClass:"full-width q-mt-xl",attrs:{loading:t.loading,percentage:t.syncProgress,"dark-percentagex":"",outline:"",dark:"",color:"primary","icon-rightx":"fal fa-sync fa-xs"},on:{click:t.syncStaff}},[t._v("\n           Sync\n          "),n("span",{attrs:{slot:"loading"},slot:"loading"},[n("q-spinner-gears",{staticClass:"on-left"}),t._v("\n            Computing...\n          ")],1)]),n("console",{staticStyle:{"min-height":"300px"}}),t.syncIsComplete&&!t.loading?n("div",[n("h1",[t._v("Summary")]),n("div",{attrs:{clsss:"row"}},[n("div",{staticClass:"col"},[n("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"primary",dark:"",readonly:"",label:"New"},model:{value:t.results.new,callback:function(e){t.$set(t.results,"new",e)},expression:"results.new"}})],1)]),n("div",{staticClass:"row full-width"},[n("div",{staticClass:"col"},[n("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"primary",dark:"",readonly:"",label:"Updated"},model:{value:t.results.updated,callback:function(e){t.$set(t.results,"updated",e)},expression:"results.updated"}})],1)]),n("div",{staticClass:"row full-width"},[n("div",{staticClass:"col"},[n("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"primary",dark:"",readonly:"",label:"Disabled"},model:{value:t.results.disabled,callback:function(e){t.$set(t.results,"disabled",e)},expression:"results.disabled"}})],1)])]):t._e()],1)])])])},w=[];function x(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,s)}return n}function k(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?x(Object(n),!0).forEach((function(e){c()(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):x(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}var j={name:"ComponentAdminSyncStaffSync",props:{},data:function(){return{id:"0",stats:{misStaffCount:0,adaStaffCount:0},misStaffCount:"",misStaff:[],columns:[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!1},{name:"lastName",label:"Surname",align:"left",field:"txtSurname",sortable:!0},{name:"firstName",label:"First Name",align:"left",field:"txtFirstname",sortable:!0},{name:"preName",label:"Pre Name",align:"left",field:"txtPreName",hidden:!0,sortable:!0},{name:"email",label:"Email",field:"txtEmailAddress",align:"left",sortable:!0},{name:"group",label:"Group",field:"intGroupID",align:"left",type:"options",filter:!0,options:[],sortable:!0},{name:"status",label:"Status",field:"txtStatus",align:"left",sortable:!0},{name:"type",label:"Type",field:"txtUserType",align:"left",sortable:!0}],loading:!1,percentage:100,syncIsComplete:!1,results:[]}},methods:k({},Object(u["b"])("sockets",["clearConsoleLog"]),{showMISStaff:function(t){this.misStaff=t.misStaff,this.columns[5].options=t.groups,this.stats=t.stats},syncStaff:function(){this.clearConsoleLog(),this.loading=!0,f.postSyncStaff(this.syncComplete)},syncComplete:function(t){this.results=t,this.loading=!1,this.syncIsComplete=!0,this.percentage=0}}),computed:k({},Object(u["c"])("sockets",["progress"]),{syncProgress:function(){return this.progress("Admin/Sync/Staff")}}),components:{Crud:p["a"],Console:m["a"]},created:function(){f.getMISStaff(this.showMISStaff)}},P=j,q=(n("b12c"),Object(v["a"])(P,C,w,!1,null,"1b9715df",null)),_=q.exports,D={name:"PageAdminSync",data:function(){return{elements:[{name:"students",label:"Students",component:O,shortcut:"s"},{name:"staff",label:"Staff",component:_,shortcut:"t"}]}},components:{toolbarPage:l["a"]},methods:{refresh:function(){}}},I=D,E=Object(v["a"])(I,s,a,!1,null,null,null);e["default"]=E.exports},a0f5:function(t,e,n){"use strict";var s=n("0cf2"),a=n.n(s);a.a},b0d4:function(t,e,n){"use strict";var s=n("b3f7"),a=n.n(s);a.a},b12c:function(t,e,n){"use strict";var s=n("c172"),a=n.n(s);a.a},b3f7:function(t,e,n){},c172:function(t,e,n){},d522:function(t,e,n){},dd14:function(t,e,n){"use strict";var s=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"console q-ma-md full-height overflow-y:scroll"},[n("p",{staticClass:"q-ma-sm"},[t._v("Console")]),n("ul",t._l(t.consoleLog,(function(e){return n("li",{key:e.lineIndex,class:{error:e.isError,indent1:1==e.indent,indent2:2==e.indent,indent3:3==e.indent}},[t._v("\n      "+t._s(e.message)+"\n    ")])})),0)])},a=[],l=(n("e125"),n("4823"),n("2e73"),n("dde3"),n("76d0"),n("0c1f"),n("8e9e")),o=n.n(l),r=n("9ce4");function i(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,s)}return n}function c(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?i(Object(n),!0).forEach((function(e){o()(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):i(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}var u={name:"AppConsole",data:function(){return{}},computed:c({},Object(r["c"])("sockets",["consoleLog"]))},d=u,f=(n("48d9"),n("2be6")),p=Object(f["a"])(d,s,a,!1,null,"7a29c78d",null);e["a"]=p.exports}}]);