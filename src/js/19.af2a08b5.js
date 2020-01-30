(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[19],{"37f84":function(t,e,s){"use strict";var n=s("a444"),a=s.n(n);a.a},8976:function(t,e,s){"use strict";s.r(e);var n=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("toolbar-page",{attrs:{elements:t.elements,default:"students"}})},a=[],l=s("08e9"),o=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("q-page",{},[s("div",{staticClass:"row"},[s("div",{staticClass:"col-8"},[s("h1",[t._v(" iSAMS Students ")]),s("crud",{ref:"crud",attrs:{dataOverride:t.misStudents,api:t.api,columns:t.columns,settings:t.settings,actions:t.actions,sortBy:"name",rowKey:"id",search:""}})],1),s("div",{staticClass:"col-4"},[s("div",{staticClass:"block border-accent q-ba-sm"},[s("div",{staticClass:"row"},[s("div",{attrs:{clsss:"col flex"}},[s("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"accent",readonly:"",label:"# MIS Students"},model:{value:t.stats.misStudentCount,callback:function(e){t.$set(t.stats,"misStudentCount",e)},expression:"stats.misStudentCount"}})],1),s("div",{staticClass:"col"},[s("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"accent",readonly:"",label:"# ADA Students"},model:{value:t.stats.adaStudentCount,callback:function(e){t.$set(t.stats,"adaStudentCount",e)},expression:"stats.adaStudentCount"}})],1)]),s("q-btn",{staticClass:"full-width q-mt-xl",attrs:{loading:t.loading,percentage:t.syncProgress,"dark-percentagex":"",outline:"",color:"accent","icon-rightx":"fal fa-sync fa-xs"},on:{click:t.syncStudents}},[t._v("\n           Sync\n          "),s("span",{attrs:{slot:"loading"},slot:"loading"},[s("q-spinner-gears",{staticClass:"on-left"}),t._v("\n            Computing...\n          ")],1)]),s("console",{staticStyle:{"min-height":"300px"}}),t.syncIsComplete&&!t.loading?s("div",[s("h1",[t._v("Summary")]),s("div",{attrs:{clsss:"row"}},[s("div",{staticClass:"col"},[s("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"accent",readonly:"",label:"New"},model:{value:t.results.new,callback:function(e){t.$set(t.results,"new",e)},expression:"results.new"}})],1)]),s("div",{staticClass:"row full-width"},[s("div",{staticClass:"col"},[s("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"accent",dark:"",readonly:"",label:"Updated"},model:{value:t.results.updated,callback:function(e){t.$set(t.results,"updated",e)},expression:"results.updated"}})],1)]),s("div",{staticClass:"row full-width"},[s("div",{staticClass:"col"},[s("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"accent",dark:"",readonly:"",label:"Disabled"},model:{value:t.results.disabled,callback:function(e){t.$set(t.results,"disabled",e)},expression:"results.disabled"}})],1)])]):t._e()],1)])])])},i=[],c=(s("e125"),s("4823"),s("2e73"),s("dde3"),s("76d0"),s("0c1f"),s("8e9e")),r=s.n(c),d=s("9ce4"),u=s("4778"),f={getMISStudents:function(t){u["a"].get("/admin/sync/misstudents").then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},postSyncStudents:function(t){u["a"].post("/admin/sync/students").then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getMISStaff:function(t){u["a"].get("/admin/sync/misstaff").then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},postSyncStaff:function(t){u["a"].post("/admin/sync/staff").then((function(e){t(e.data)})).catch((function(t){console.log(t)}))}},p=s("d612"),m=s("dd14");function b(t,e){var s=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),s.push.apply(s,n)}return s}function g(t){for(var e=1;e<arguments.length;e++){var s=null!=arguments[e]?arguments[e]:{};e%2?b(Object(s),!0).forEach((function(e){r()(t,e,s[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(s)):b(Object(s)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(s,e))}))}return t}var y={name:"ComponentAdminSyncStudentSync",props:{},data:function(){return{id:"0",stats:{misStudentCount:0,adaStudentCount:0},misStudentCount:"",misStudents:[],columns:[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"lastName",required:!0,label:"Name",align:"left",field:"txtSurname",sortable:!0},{name:"firstName",required:!0,label:"",align:"left",field:"txtForename",sortable:!0},{name:"gender",label:"M/F",field:"txtGender",align:"left",sortable:!0},{name:"house",label:"Hs",field:"txtBoardingHouse",align:"left",sortable:!0}],loading:!1,percentage:100,syncIsComplete:!1,results:[],channel:"lab.crud.basic"}},methods:g({},Object(d["b"])("sockets",["clearConsoleLog"]),{showMISStudents:function(t){this.misStudents=t.misStudents,this.stats=t.stats},syncStudents:function(){this.clearConsoleLog(),this.loading=!0,f.postSyncStudents(this.syncComplete)},syncComplete:function(t){this.results=t,this.loading=!1,this.syncIsComplete=!0,this.percentage=0}}),computed:g({},Object(d["c"])("sockets",["progress"]),{syncProgress:function(){return this.progress("Admin/Sync/Students")}}),components:{Crud:p["a"],Console:m["a"]},created:function(){f.getMISStudents(this.showMISStudents)}},S=y,h=(s("f8fd"),s("2be6")),C=Object(h["a"])(S,o,i,!1,null,"fc80faf2",null),v=C.exports,x=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("q-page",{},[s("h1",[t._v("MIS Staff")]),s("div",{staticClass:"row"},[s("div",{staticClass:"col-8"},[s("crud",{ref:"crud",attrs:{dataOverride:t.misStaff,api:t.api,columns:t.columns,settings:t.settings,actions:t.actions,sortBy:"lastName",rowKey:"id",search:"",filterchips:""}})],1),s("div",{staticClass:"col-4"},[s("div",{staticClass:"block border-accent q-ba-sm"},[s("div",{staticClass:"row"},[s("div",{attrs:{clsss:"col flex"}},[s("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"accent",dark:"",readonly:"",label:"# MIS Staff"},model:{value:t.stats.misStaffCount,callback:function(e){t.$set(t.stats,"misStaffCount",e)},expression:"stats.misStaffCount"}})],1),s("div",{staticClass:"col"},[s("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"accent",dark:"",readonly:"",label:"# ADA Staff"},model:{value:t.stats.adaStaffCount,callback:function(e){t.$set(t.stats,"adaStaffCount",e)},expression:"stats.adaStaffCount"}})],1)]),s("q-btn",{staticClass:"full-width q-mt-xl",attrs:{loading:t.loading,percentage:t.syncProgress,"dark-percentagex":"",outline:"",dark:"",color:"accent","icon-rightx":"fal fa-sync fa-xs"},on:{click:t.syncStaff}},[t._v("\n           Sync\n          "),s("span",{attrs:{slot:"loading"},slot:"loading"},[s("q-spinner-gears",{staticClass:"on-left"}),t._v("\n            Computing...\n          ")],1)]),s("console",{staticStyle:{"min-height":"300px"}}),t.syncIsComplete&&!t.loading?s("div",[s("h1",[t._v("Summary")]),s("div",{attrs:{clsss:"row"}},[s("div",{staticClass:"col"},[s("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"accent",dark:"",readonly:"",label:"New"},model:{value:t.results.new,callback:function(e){t.$set(t.results,"new",e)},expression:"results.new"}})],1)]),s("div",{staticClass:"row full-width"},[s("div",{staticClass:"col"},[s("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"accent",dark:"",readonly:"",label:"Updated"},model:{value:t.results.updated,callback:function(e){t.$set(t.results,"updated",e)},expression:"results.updated"}})],1)]),s("div",{staticClass:"row full-width"},[s("div",{staticClass:"col"},[s("q-input",{attrs:{type:"text",align:"left",suffix:"",color:"accent",dark:"",readonly:"",label:"Disabled"},model:{value:t.results.disabled,callback:function(e){t.$set(t.results,"disabled",e)},expression:"results.disabled"}})],1)])]):t._e()],1)])])])},w=[];function O(t,e){var s=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),s.push.apply(s,n)}return s}function k(t){for(var e=1;e<arguments.length;e++){var s=null!=arguments[e]?arguments[e]:{};e%2?O(Object(s),!0).forEach((function(e){r()(t,e,s[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(s)):O(Object(s)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(s,e))}))}return t}var j={name:"ComponentAdminSyncStaffSync",props:{},data:function(){return{id:"0",stats:{misStaffCount:0,adaStaffCount:0},misStaffCount:"",misStaff:[],columns:[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!1},{name:"lastName",label:"Surname",align:"left",field:"txtSurname",sortable:!0},{name:"firstName",label:"First Name",align:"left",field:"txtFirstname",sortable:!0},{name:"preName",label:"Pre Name",align:"left",field:"txtPreName",hidden:!0,sortable:!0},{name:"email",label:"Email",field:"txtEmailAddress",align:"left",sortable:!0},{name:"group",label:"Group",field:"intGroupID",align:"left",type:"options",filter:!0,options:[],sortable:!0},{name:"status",label:"Status",field:"txtStatus",align:"left",sortable:!0},{name:"type",label:"Type",field:"txtUserType",align:"left",sortable:!0}],loading:!1,percentage:100,syncIsComplete:!1,results:[]}},methods:k({},Object(d["b"])("sockets",["clearConsoleLog"]),{showMISStaff:function(t){this.misStaff=t.misStaff,this.columns[5].options=t.groups,this.stats=t.stats},syncStaff:function(){this.clearConsoleLog(),this.loading=!0,f.postSyncStaff(this.syncComplete)},syncComplete:function(t){this.results=t,this.loading=!1,this.syncIsComplete=!0,this.percentage=0}}),computed:k({},Object(d["c"])("sockets",["progress"]),{syncProgress:function(){return this.progress("Admin/Sync/Staff")}}),components:{Crud:p["a"],Console:m["a"]},created:function(){f.getMISStaff(this.showMISStaff)}},P=j,q=(s("37f84"),Object(h["a"])(P,x,w,!1,null,"41fb833b",null)),I=q.exports,_={name:"PageAdminSync",data:function(){return{elements:[{name:"students",label:"Students",component:v,shortcut:"s"},{name:"staff",label:"Staff",component:I,shortcut:"t"}]}},components:{toolbarPage:l["a"]},methods:{refresh:function(){}}},D=_,M=Object(h["a"])(D,n,a,!1,null,null,null);e["default"]=M.exports},a444:function(t,e,s){},dcdc:function(t,e,s){},f8fd:function(t,e,s){"use strict";var n=s("dcdc"),a=s.n(n);a.a}}]);