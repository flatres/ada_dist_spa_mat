(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["0cce225a"],{3512:function(t,e,a){"use strict";var n=a("5e2c"),s=a.n(n);s.a},"5e2c":function(t,e,a){},"79ed":function(t,e,a){},"80b6":function(t,e,a){"use strict";var n=a("79ed"),s=a.n(n);s.a},aef9:function(t,e,a){"use strict";var n=a("c3cd"),s=a.n(n);s.a},c3cd:function(t,e,a){},d2f6:function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("q-page",[a("q-tabs",{staticClass:"narrow",attrs:{id:"ad",color:"dark","underline-color":"primary"},model:{value:t.adTab,callback:function(e){t.adTab=e},expression:"adTab"}},[a("q-tab",{attrs:{slot:"title",default:"",icon:"fal fa-child fa-sm",label:"Me",name:"me"},slot:"title"}),a("q-tab",{attrs:{slot:"title",icon:"fal fa-users fa-sm",label:"All Staff",name:"staff"},slot:"title"}),a("q-tab-pane",{attrs:{name:"me"}},[a("me")],1),a("q-tab-pane",{attrs:{name:"cr"}}),a("q-tab-pane",{attrs:{name:"staff"}},[a("all-staff")],1)],1)],1)},s=[];n._withStripped=!0;var o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("q-page",{},[a("div",{staticClass:"row fit"},[a("tree-view",{attrs:{data:t.jsonSource,options:{maxDepth:1}}})],1)])},i=[];o._withStripped=!0;var l=a("3156"),r=a.n(l),c=a("2f62"),u=a("be3b"),f={getAllStaff:function(t){u["a"].get("/admin/activeDirectory/allStaff").then(function(e){t(e.data),console.log(e)}).catch(function(t){console.log(t)})},getMe:function(t){u["a"].get("/admin/activeDirectory/me").then(function(e){console.log(e),t(e.data["0"])}).catch(function(t){console.log(t)})},getIsamsInfo:function(t,e){u["a"].get("/admin/activeDirectory/isams/"+t).then(function(t){console.log(t),e(t.data)}).catch(function(t){console.log(t)})}},d={name:"ComponentAdminAdMe",props:{},data:function(){return{jsonSource:{root:{id:"1"}}}},methods:r()({},Object(c["b"])("sockets",["clearConsoleLog"])),computed:{},components:{},created:function(){var t=this;f.getMe(function(e){t.jsonSource=e,console.log(t.jsonSource)})}},m=d,p=(a("3512"),a("2877")),g=Object(p["a"])(m,o,i,!1,null,null,null);g.options.__file="me.vue";var v=g.exports,h=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("q-page",{},[a("div",{staticClass:"row fit"},[a("div",{staticClass:"col-8 scroll"},[a("q-table",{attrs:{data:t.filteredUsers,columns:t.columns,filter:t.filter,separator:t.separator,pagination:t.paginationControl,"row-key":t.login,selectionx:"single",color:"primary",loading:t.loading,dark:"",dense:""},on:{"update:pagination":function(e){t.paginationControl=e}},scopedSlots:t._u([{key:"top-left",fn:function(e){return[a("q-search",{staticClass:"col-6",attrs:{"hide-underline":"",color:"secondary",dark:""},model:{value:t.filter,callback:function(e){t.filter=e},expression:"filter"}})]}},{key:"top-right",fn:function(e){return[a("q-select",{staticClass:"q-mx-md",staticStyle:{color:"white"},attrs:{color:"white",filter:"",dark:"",options:t.groupList,"hide-underline":""},model:{value:t.group,callback:function(e){t.group=e},expression:"group"}})]}},{key:"body",fn:function(e){return[a("q-tr",{class:{"bg-secondary":t.selectedUserLogin===e.row.login,"text-black":t.selectedUserLogin===e.row.login},attrs:{props:e},nativeOn:{click:function(a){t.clickRow(e.row)}}},[a("q-td",{key:"lastName",attrs:{props:e}},[t._v("\n                "+t._s(e.row.lastName)+"\n              ")]),a("q-td",{key:"firstName",attrs:{props:e}},[t._v("\n                "+t._s(e.row.firstName)+"\n              ")]),a("q-td",{key:"login",attrs:{props:e}},[t._v("\n                "+t._s(e.row.login)+"\n              ")]),a("q-td",{key:"email",attrs:{props:e}},[t._v("\n                "+t._s(e.row.email)+"\n              ")])],1)]}}])})],1),a("div",{staticClass:"col-4 q-pl-md"},[a("h4",[t._v(t._s(t.firstName+" "+t.lastName))]),a("p",[t._v(t._s(t.login))]),a("p",[t._v(t._s(t.email))]),t.selectedUserLogin?a("h5",{staticClass:"text-primary"},[t._v("Groups")]):t._e(),a("ul",t._l(t.groups,function(e){return a("li",{key:e,staticClass:"text-secondary"},[t._v(t._s(e))])})),t.isamsData?a("div",[a("h5",{staticClass:"text-primary"},[t._v("iSams Data")]),a("p",[t._v(t._s(t.isamsData.userCode))]),a("ul",t._l(t.isamsData.tutees,function(e){return a("li",{key:e.txtSchoolId,staticClass:"text-secondary"},[t._v("\n            "+t._s(e.lastName+" "+e.initials+" "+e.boarding)+"\n          ")])}))]):t._e()])])])},b=[];h._withStripped=!0;a("6762"),a("2fdb");var _={name:"ComponentAdminAdMe",props:{},data:function(){return{login:null,group:"",groupList:[],users:[],filter:"",separator:"horizontal",selection:"single",selectedUser:null,selectedUserLogin:null,pagination:{page:1},paginationControl:{rowsPerPage:40,page:1,sortBy:"lastName"},loading:!1,dark:!0,columns:[{name:"lastName",required:!0,label:"Last name",align:"left",field:"lastName",sortable:!0},{name:"firstName",label:"First Name",field:"firstName",sortable:!0},{name:"login",label:"Login",field:"login",sortable:!0},{name:"email",label:"Email",field:"email",sortable:!0}],firstName:"",lastName:"",email:"",groups:[],isamsData:null}},methods:r()({},Object(c["b"])("sockets",["clearConsoleLog"]),{clickRow:function(t){var e=this;this.selectedUserLogin=t.login,this.selectedUser=t,this.firstName=t.firstName,this.lastName=t.lastName,this.login=t.login,this.email=t.email,this.groups=t.groups,f.getIsamsInfo(t.login,function(t){e.isamsData=t,console.log(t)})}}),computed:{filteredUsers:function(){var t=this;if(""===this.group)return this.users;var e=this.users.filter(function(e,a){return e.groups.includes(t.group)});return e}},components:{},created:function(){var t=this;this.loading=!0,f.getAllStaff(function(e){t.users=e.users,t.groupList=e.groups,t.loading=!1})}},k=_,w=(a("aef9"),Object(p["a"])(k,h,b,!1,null,null,null));w.options.__file="allStaff.vue";var y=w.exports,q={name:"PageAdminAd",data:function(){return{adTab:null}},components:{me:v,allStaff:y},methods:{},created:function(){}},C=q,N=(a("80b6"),Object(p["a"])(C,n,s,!1,null,null,null));N.options.__file="activeDirectory.vue";e["default"]=N.exports}}]);