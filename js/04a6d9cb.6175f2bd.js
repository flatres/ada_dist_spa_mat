(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["04a6d9cb"],{"2f7a":function(e,t,o){},"302e":function(e,t,o){"use strict";var a=o("ac38"),n=o.n(a);n.a},"32d1":function(e,t,o){},"6a25":function(e,t,o){},7328:function(e,t,o){"use strict";o.r(t);var a=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("q-page",{staticClass:"no-scroll"},[o("q-tabs",{staticClass:"narrow",attrs:{id:"access",color:"dark","underline-color":"primary"},model:{value:e.accessTab,callback:function(t){e.accessTab=t},expression:"accessTab"}},[o("q-tab",{attrs:{slot:"title",default:"",icon:"fal fa-sync-alt fa-sm",label:"Roles",name:"roles"},slot:"title"}),o("q-tab",{attrs:{slot:"title",label:"Log",icon:"fal fa-scroll fa-sm",exact:"",name:"log"},slot:"title"}),o("q-tab",{attrs:{slot:"title",icon:"fal fa-cogs fa-sm",label:"Structure",name:"structure"},slot:"title"}),o("q-tab-pane",{attrs:{name:"roles"}},[o("roles")],1),o("q-tab-pane",{attrs:{name:"structure"}},[o("structure")],1),o("q-tab-pane",{attrs:{name:"log"}},[o("users")],1)],1)],1)},n=[];a._withStripped=!0;var s=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("q-page",{staticClass:" row"},[o("div",{staticClass:"col-3 q-mr-md",staticStyle:{"border-right":"1px solid grey"}},[o("q-list",{attrs:{dark:"",separatoxr:"",dense:"","no-border":""}},[o("q-list-header",{staticClass:"row q-pt-sm hidden no-margin",staticStyle:{"border-bottom":"1px solid grey",height:"50px"}},[o("span",{staticClass:"col"},[e._v("Roles")]),o("q-btn",{staticClass:"q-mb-md",attrs:{icon:"fa fa-plus",color:"green",dense:"",size:"sm",right:""},on:{click:e.newRole}})],1),o("q-item",[o("q-btn",{staticClass:"q-mb-sm",attrs:{icon:"fa fa-plus",color:"dark",dense:"",size:"sm",label:"new",right:""},on:{click:e.newRole}})],1),e._l(e.roles,function(t){return o("q-item",{key:t.value,class:{"bg-dark":t.value==e.role},nativeOn:{click:function(o){e.clickRole(t.value,t.label)}}},[o("q-item-main",{staticClass:"capitalize",attrs:{label:t.label}}),o("q-item-side",{attrs:{right:""}},[o("q-btn",{staticClass:"no-border",attrs:{flat:"",round:"",dense:"",icon:"more_vert"}},[o("q-popover",[o("q-list",{attrs:{link:""}},[o("q-item",{directives:[{name:"close-overlay",rawName:"v-close-overlay"}]},[o("q-item-main",{attrs:{label:"Rename"}})],1),o("q-item",{directives:[{name:"close-overlay",rawName:"v-close-overlay"}],nativeOn:{click:function(o){e.deleteRole(t.value)}}},[o("q-item-main",{attrs:{label:"Delete"}})],1)],1)],1)],1)],1)],1)})],2)],1),o("div",{staticClass:"col"},[o("q-tabs",{staticClass:"narrow",attrs:{color:"dark","underline-color":"primary"},model:{value:e.accessTab,callback:function(t){e.accessTab=t},expression:"accessTab"}},[o("q-tab",{attrs:{slot:"title",default:"",icon:"fal fa-book fa-sm",label:"Pages",name:"pages"},slot:"title"}),o("q-tab",{attrs:{slot:"title",label:"Users",icon:"fal fa-users fa-sm",exact:"",name:"users"},slot:"title"}),o("q-tab",{attrs:{slot:"title",icon:"fal fa-plus fa-sm",label:"Add Users",name:"add"},slot:"title"}),o("q-tab-pane",{attrs:{name:"pages"}},[o("p",{staticClass:"text-primary capitalize"},[e._v(e._s(e.roleName))]),e.role?o("q-tree",{attrs:{nodes:e.tree,color:"primary","default-expand-all":"",ticked:e.ticked,"tick-strategy":e.tickStrategy,filter:e.tickFilter,"node-key":"id","label-key":"name",dark:""},on:{"update:ticked":function(t){e.ticked=t}}}):e._e()],1),o("q-tab-pane",{attrs:{name:"add"}},[o("role-add-users",{attrs:{role:e.role,roleName:e.roleName}})],1),o("q-tab-pane",{attrs:{name:"users"}},[o("role-users",{attrs:{role:e.role,roleName:e.roleName}})],1)],1)],1)])},l=[];s._withStripped=!0;var r=o("3156"),i=o.n(r),c=o("2f62"),d=o("be3b"),u={getRoles:function(e){d["a"].get("/admin/access/roles").then(function(t){e(t.data)}).catch(function(e){console.log(e)})},getModuleTree:function(e){d["a"].get("/admin/access/module/tree").then(function(t){e(t.data)}).catch(function(e){console.log(e)})},getRolePages:function(e,t){d["a"].get("/admin/access/roles/pages/"+e).then(function(e){t(e.data)}).catch(function(e){console.log(e)})},getRoleUsers:function(e,t){d["a"].get("/admin/access/roles/users/"+e).then(function(e){t(e.data)}).catch(function(e){console.log(e)})},putRolePages:function(e,t){d["a"].put("/admin/access/roles/pages",{id:e,pages:t}).then(function(e){}).catch(function(e){console.log(e)})},deleteRole:function(e,t){d["a"].delete("/admin/access/roles/"+e).then(function(e){t(e.data)}).catch(function(e){console.log(e)})},deleteRoleUser:function(e,t,o){d["a"].delete("/admin/access/roles/user/"+e+"/"+t).then(function(e){o(e.data)}).catch(function(e){console.log(e)})},postRole:function(e,t){d["a"].post("/admin/access/roles",{name:e}).then(function(e){t(e.data)}).catch(function(e){console.log(e)})},postUser:function(e,t,o){d["a"].post("/admin/access/roles/user",{roleId:e,userId:t}).then(function(e){o(e.data)}).catch(function(e){console.log(e)})},getAllUsers:function(e,t){d["a"].get("/admin/access/roles/allusers").then(function(e){t(e.data)}).catch(function(e){console.log(e)})}},f=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",[o("div",{staticClass:"row items-center"},[o("q-input",{staticClass:"col",attrs:{placeholder:"Search",clearable:"","hide-underlines":"",dark:""},model:{value:e.filter,callback:function(t){e.filter=t},expression:"filter"}})],1),o("q-table",{staticClass:"full-height no-border no-shadow",staticStyle:{"min-widthx":"800px","min-height":"710px"},attrs:{data:e.roleUsers,columns:e.columns,separator:e.separator,"row-key":e.id,filter:e.filter,pagination:e.paginationControl,color:"primary","hide-header":"",dark:"",dense:""},on:{"update:pagination":function(t){e.paginationControl=t}},scopedSlots:e._u([{key:"body",fn:function(t){return[o("q-tr",{attrs:{props:t}},[o("q-td",{key:"firstName",attrs:{props:t}},[o("q-btn",{attrs:{flat:"",round:"",dense:"",icon:"more_vert"}},[o("q-popover",[o("q-list",{attrs:{link:""}},[o("q-item",{directives:[{name:"close-overlay",rawName:"v-close-overlay"}]},[o("q-item-main",{attrs:{label:"Log"}})],1),o("q-item",{directives:[{name:"close-overlay",rawName:"v-close-overlay"}],nativeOn:{click:function(o){e.deleteUser(t.row.id)}}},[o("q-item-main",{attrs:{label:"Delete"}})],1)],1)],1)],1),o("span",[e._v(e._s(t.row.firstName))])],1),o("q-td",{key:"lastName",attrs:{props:t}},[e._v(e._s(t.row.lastName))])],1)]}}])})],1)},m=[];f._withStripped=!0;var p={name:"ComponentRoleUsers",props:["role","roleName"],data:function(){return{id:null,roleUsers:[],roleUsersLoading:!1,filter:"",columns:[{name:"firstName",label:"",field:"firstName",align:"left"},{name:"lastName",label:"",field:"lastName",align:"left"}],paginationControl:{rowsPerPage:40,page:1,sortBy:"txtBusCode"},separator:"horizontal"}},watch:{role:function(){this.getUsers()}},methods:{getUsers:function(){var e=this;u.getRoleUsers(this.role,function(t){e.roleUsers=t,e.roleUsersLoading=!0})},deleteUser:function(e){var t=this;this.roleUsersLoading=!0,u.deleteRoleUser(this.role,e,function(){t.roleUsersLoading=!1,t.$q.notify("User removed."),t.getUsers()})}},computed:{},components:{},created:function(){this.getUsers()}},h=p,g=(o("cfb4"),o("2877")),v=Object(g["a"])(h,f,m,!1,null,"2cf89eea",null);v.options.__file="roleUsers.vue";var b=v.exports,q=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",[o("div",{staticClass:"row items-center"},[o("q-input",{staticClass:"col",attrs:{placeholder:"Search",clearable:"","hide-underlines":"",dark:""},model:{value:e.filter,callback:function(t){e.filter=t},expression:"filter"}})],1),o("q-table",{staticClass:"full-height no-border no-shadow",staticStyle:{"min-widthx":"800px","min-height":"710px"},attrs:{data:e.users,loading:e.usersLoading,columns:e.columns,separator:e.separator,"row-key":e.id,filter:e.filter,pagination:e.paginationControl,color:"primary","hide-header":"",dark:"",dense:""},on:{"update:pagination":function(t){e.paginationControl=t}},scopedSlots:e._u([{key:"body",fn:function(t){return[o("q-tr",{attrs:{props:t}},[o("q-td",{key:"firstName",attrs:{props:t}},[o("q-btn",{staticClass:"no-border",attrs:{flat:"",round:"",dense:"",icon:"more_vert"}},[o("q-popover",[o("q-list",{attrs:{link:""}},[o("q-item",{directives:[{name:"close-overlay",rawName:"v-close-overlay"}],nativeOn:{click:function(o){e.addUser(t.row.id)}}},[o("q-item-main",{attrs:{label:"Add to role: "+e.roleName}})],1)],1)],1)],1),o("span",[e._v(e._s(t.row.firstName))])],1),o("q-td",{key:"lastName",attrs:{props:t}},[e._v(e._s(t.row.lastName))]),o("q-td")],1)]}}])})],1)},k=[];q._withStripped=!0;var C={name:"ComponentRoleAddUsers",props:["role","roleName"],data:function(){return{id:null,users:[],usersLoading:!1,filter:"",columns:[{name:"firstName",label:"",field:"firstName",align:"left"},{name:"lastName",label:"",field:"lastName",align:"left"}],paginationControl:{rowsPerPage:40,page:1,sortBy:"txtBusCode"},separator:"horizontal"}},watch:{role:function(){this.getUsers()}},methods:{addUser:function(e){var t=this;this.usersLoading=!0,u.postUser(this.role,e,function(){t.usersLoading=!1,t.$q.notify("User added")})},getUsers:function(){var e=this;this.usersLoading=!0,u.getAllUsers(this.role,function(t){e.users=t,e.usersLoading=!1})}},computed:{},components:{},created:function(){this.getUsers()}},y=C,w=(o("b805"),Object(g["a"])(y,q,k,!1,null,"5dbdd1ad",null));w.options.__file="roleAddUsers.vue";var x=w.exports,_={name:"ComponentAdminAccessRoles",props:{},data:function(){return{tree:[],ticked:[],tickStrategy:"leaf-filtered",tickFilter:null,role:null,roleName:"",roles:[],moduleIcon:{students:"fal fa-child",transport:"fal fa-space-shuttle"}}},methods:{clickRole:function(e,t){this.role=e,this.roleName=t},newRole:function(){var e=this;this.$q.dialog({title:"New Role",message:"Name?",prompt:{model:"",type:"text"},cancel:!0,color:"primary"}).then(function(t){u.postRole(t,function(t){e.getRoles(t.id)})}).catch(function(){e.$q.notify("Cancelled")})},deleteRole:function(e){var t=this;this.$q.dialog({title:"Confirm",message:"Delete this Page? The consequences of an error are grave.",ok:"Yes",cancel:"NO!!!"}).then(function(){u.deleteRole(e,t.getRoles),t.role=null,t.roleName=""}).catch(function(){t.$q.notify("Cancelled")})},getRoles:function(e){var t=this;u.getRoles(function(o){t.roles=o,e&&(t.role=e)})}},computed:i()({},Object(c["c"])("user",["permissions"])),watch:{ticked:function(){u.putRolePages(this.role,this.ticked)},role:function(){var e=this;this.roleUsersLoading=!0,u.getRolePages(this.role,function(t){e.ticked=t})}},components:{roleUsers:b,roleAddUsers:x},created:function(){var e=this;this.getRoles(),u.getModuleTree(function(t){e.tree=t})}},N=_,R=(o("ddca"),Object(g["a"])(N,s,l,!1,null,"43dd04b4",null));R.options.__file="roles.vue";var U=R.exports,P=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("q-page",{staticClass:"q-ml-xl"},[o("div",{staticClass:"row"},[o("div",{staticClass:"col-8 q-mt-xl"},[o("q-icon",{attrs:{name:"fal fa-plus-circle",color:"primary"},nativeOn:{click:function(t){return e.newModule(t)}}}),o("q-tree",{staticClass:"q-mt-lg",attrs:{nodes:e.tree,color:"primary","default-expand-all":"","node-key":"id","label-key":"name",dark:""},scopedSlots:e._u([{key:"default-header",fn:function(t){return o("div",{},[t.node.module?o("div",[o("span",{staticClass:"q-mx-sm"},[o("span",{staticClass:"inline-block text-center",staticStyle:{"min-width":"20px","min-height":"20px"}},[o("q-icon",{style:{color:t.node.color},attrs:{name:""===t.node.icon?"fal fa-empty-set":t.node.icon}})],1),o("q-popup-edit",{on:{save:function(o){e.saveModuleIcon(t.node.moduleId,t.node.icon)}},model:{value:t.node.icon,callback:function(o){e.$set(t.node,"icon",o)},expression:"prop.node.icon"}},[o("q-field",[o("q-input",{model:{value:t.node.icon,callback:function(o){e.$set(t.node,"icon",o)},expression:"prop.node.icon"}})],1)],1)],1),o("span",[e._v("\n              "+e._s(t.node.name)+"\n              "),o("q-popup-edit",{attrs:{dark:""},on:{save:function(o){e.saveModuleName(t.node.moduleId,t.node.name)}},model:{value:t.node.name,callback:function(o){e.$set(t.node,"name",o)},expression:"prop.node.name"}},[o("q-field",[o("q-input",{model:{value:t.node.name,callback:function(o){e.$set(t.node,"name",o)},expression:"prop.node.name"}})],1)],1)],1),o("span",{staticClass:"text-weight-thin no-shadow"},[o("q-btn",{staticClass:"no-shadow",staticStyle:{width:"20px"},attrs:{icon:"fal fa-palette fa-xs"},on:{click:function(o){o.stopPropagation(),e.setColor(t.node.moduleId,t.node.name,t.node.color)}}}),o("q-icon",{staticClass:"q-mt-xs q-mr-xs q-ml-sm",attrs:{name:"fal fa-trash",color:"red"},nativeOn:{click:function(o){e.deleteModule(t.node.moduleId)}}}),o("q-btn",{staticClass:"no-shadow",staticStyle:{width:"20px"},attrs:{icon:"fal fa-plus fa-xs"},on:{click:function(o){o.stopPropagation(),e.newPage(t.node.moduleId)}}})],1)]):o("div",{},[o("span",{staticClass:"row text-primary"},[e._v("\n              "+e._s(t.node.name)+"\n              "),o("div",[o("q-icon",{staticClass:"q-mx-md",attrs:{name:"fa fa-edit",color:"primary"}}),o("q-popup-edit",{attrs:{dark:""},on:{save:function(o){e.savePage(t.node.id,t.node.name)}},model:{value:t.node.name,callback:function(o){e.$set(t.node,"name",o)},expression:"prop.node.name"}},[o("q-field",[o("q-input",{model:{value:t.node.name,callback:function(o){e.$set(t.node,"name",o)},expression:"prop.node.name"}})],1)],1)],1),o("q-icon",{staticClass:"q-mt-xs q-mr-md",attrs:{name:"fal fa-trash",color:"red"},nativeOn:{click:function(o){e.deletePage(t.node.id)}}}),o("q-icon",{staticClass:"q-mt-xs",attrs:{name:"fal fa-info-circle"}},[o("q-tooltip",{attrs:{anchor:"center right",self:"center left",offset:[10,10]}},[o("div",e._l(t.node.roles,function(t){return o("p",{key:t.id,staticClass:"capitalize"},[e._v("\n                      "+e._s(t.name)+"\n                    ")])}))])],1)],1)])])}}])})],1),o("div",{staticClass:"col-4"})]),o("color-picker",{attrs:{color:e.colorModuleColor,show:e.showColorPicker,name:e.colorModuleName},on:{"update:color":function(t){e.colorModuleColor=t},"update:show":function(t){e.showColorPicker=t},saveColor:e.saveColor}})],1)},$=[];P._withStripped=!0;var M={deleteModule:function(e,t){d["a"].delete("/admin/access/structure/module/"+e).then(function(e){t(e.data)}).catch(function(e){console.log(e)})},postModuleIcon:function(e,t,o){d["a"].post("/admin/access/structure/module/icon",{id:e,icon:t}).then(function(e){o(e.data)}).catch(function(e){console.log(e)})},postModuleName:function(e,t,o){d["a"].post("/admin/access/structure/module/name",{id:e,name:t}).then(function(e){o(e.data)}).catch(function(e){console.log(e)})},postModule:function(e,t){d["a"].post("/admin/access/structure/module",{name:e}).then(function(e){t(e.data)}).catch(function(e){console.log(e)})},postPage:function(e,t,o){d["a"].post("/admin/access/structure/module/page",{moduleId:e,name:t}).then(function(e){o(e.data)}).catch(function(e){console.log(e)})},putPage:function(e,t,o){d["a"].put("/admin/access/structure/module/page",{id:e,name:t}).then(function(e){o(e.data)}).catch(function(e){console.log(e)})},deletePage:function(e,t){d["a"].delete("/admin/access/structure/module/page/"+e).then(function(e){t(e.data)}).catch(function(e){console.log(e)})},postColor:function(e,t,o){d["a"].post("/admin/access/structure/module/color",{id:e,color:t}).then(function(e){o(e.data)}).catch(function(e){console.log(e)})}},S=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("q-dialog",{attrs:{"stack-buttons":"","prevent-close":"",dark:""},scopedSlots:e._u([{key:"buttons",fn:function(t){return[o("q-btn",{attrs:{label:"Save Color",stylexx:"background:black",stylex:{color:e.currentColor}},on:{click:e.saveColor}}),o("q-btn",{attrs:{flat:"",label:"No thanks"},on:{click:e.cancelMe}})]}}]),model:{value:e.showDialog,callback:function(t){e.showDialog=t},expression:"showDialog"}},[o("span",{attrs:{slot:"title"},slot:"title"},[e._v("Choose Color")]),o("span",{staticClass:"capitalize",attrs:{slot:"message"},slot:"message"},[e._v(e._s(e.nameText))]),o("div",{staticClass:"no-scroll",attrs:{slot:"body"},slot:"body"},[o("q-color-picker",{staticStyle:{"max-width":"300px"},attrs:{"format-model":"hex","no-parent-field":""},model:{value:e.currentColor,callback:function(t){e.currentColor=t},expression:"currentColor"}})],1)])},T=[];S._withStripped=!0;o("7f7f");var O={data:function(){return{}},props:["show","color","name"],computed:{showDialog:{get:function(){return this.show},set:function(e){this.$emit("update:show",!1)}},currentColor:{get:function(){return this.color},set:function(e){this.$emit("update:color",e)}},nameText:function(){return this.name}},methods:{cancelMe:function(){this.$emit("update:show",!1)},saveColor:function(){console.log(this.currentColor),this.$emit("saveColor",this.currentColor),this.$emit("update:show",!1)}},created:function(){}},I=O,A=Object(g["a"])(I,S,T,!1,null,null,null);A.options.__file="colorPicker.vue";var L=A.exports,D={name:"ComponentAdminAccessRoles",props:{},data:function(){return{showColorPicker:!1,tree:[],ticked:[],tickStrategy:"leaf-filtered",tickFilter:null,role:null,roles:[],moduleIcon:{students:"fal fa-child",transport:"fal fa-space-shuttle"},colorModuleId:null,colorModulecolor:null}},methods:{saveColor:function(e){var t=this;M.postColor(this.colorModuleId,this.colorModuleColor,function(){t.getTree()})},setColor:function(e,t,o){this.colorModuleColor=o,this.colorModuleName=t,this.colorModuleId=e,this.showColorPicker=!0},deleteModule:function(e){var t=this;this.$q.dialog({title:"Confirm",message:"Delete this Module? The consequences of an error are grave.",ok:"Yes",cancel:"NO!!!"}).then(function(){M.deleteModule(e,function(){t.$q.notify("Deleted"),t.getTree()})}).catch(function(){t.$q.notify("Cancelled")})},getTree:function(){var e=this;u.getModuleTree(function(t){e.tree=t})},saveModuleIcon:function(e,t){var o=this;M.postModuleIcon(e,t,function(){o.getTree()})},saveModuleName:function(e,t){var o=this;M.postModuleName(e,t,function(){o.getTree()})},newModule:function(){var e=this;this.$q.dialog({title:"New Module",message:"Name?",prompt:{model:"",type:"text"},cancel:!0,color:"primary"}).then(function(t){M.postModule(t,function(){e.getTree()})}).catch(function(){e.$q.notify("Cancelled")})},newPage:function(e){var t=this;this.$q.dialog({title:"New Page",message:"Name?",prompt:{model:"",type:"text"},cancel:!0,color:"primary"}).then(function(o){M.postPage(e,o,function(){t.getTree()})}).catch(function(){t.$q.notify("Cancelled")})},savePage:function(e,t){var o=this;M.putPage(e,t,function(){o.getTree()})},deletePage:function(e){var t=this;this.$q.dialog({title:"Confirm",message:"Delete this Page? The consequences of an error are grave.",ok:"Yes",cancel:"NO!!!"}).then(function(){M.deletePage(e,function(){t.$q.notify("Deleted"),t.getTree()})}).catch(function(){t.$q.notify("Cancelled")})}},computed:i()({},Object(c["c"])("user",["permissions"]),{test:function(){return console.log(this.showColorPicker),this.showColorPicker}}),watch:{ticked:function(){console.log(this.ticked)},role:function(){}},components:{colorPicker:L},created:function(){var e=this;u.getRoles(function(t){e.roles=t}),this.getTree()}},j=D,z=(o("c7fb"),Object(g["a"])(j,P,$,!1,null,"47bdb827",null));z.options.__file="structure.vue";var E=z.exports,B=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("q-page",{staticClass:" row"},[o("div",{staticClass:"col-3 q-mr-md"},[o("q-list",{attrs:{dark:"",separator:""}},[o("q-list-header",{staticClass:"row q-pt-sm",staticStyle:{"border-bottom":"1px solid grey",height:"50px"}},[o("span",{staticClass:"col"},[e._v("Roles")])]),e._l(e.roles,function(t){return o("q-item",{key:t.value,class:{"bg-dark":t.value==e.role},nativeOn:{click:function(o){e.clickRole(t.value,t.label)}}},[o("q-item-main",{staticClass:"capitalize",attrs:{label:t.label}}),o("q-item-side",{attrs:{right:""}},[o("q-btn",{attrs:{flat:"",round:"",dense:"",icon:"more_vert"}},[o("q-popover",[o("q-list",{attrs:{link:""}},[o("q-item",{directives:[{name:"close-overlay",rawName:"v-close-overlay"}]},[o("q-item-main",{attrs:{label:"Rename"}})],1),o("q-item",{directives:[{name:"close-overlay",rawName:"v-close-overlay"}],nativeOn:{click:function(o){e.deleteRole(t.value)}}},[o("q-item-main",{attrs:{label:"Delete"}})],1)],1)],1)],1)],1)],1)})],2)],1),o("div",{staticClass:"col"},[o("q-btn-group",[o("q-btn",{attrs:{color:"dark",label:"users",icon:"timeline"}}),o("q-btn",{attrs:{color:"dark",icon:"visibility"}}),o("q-btn",{attrs:{color:"dark",icon:"update"}})],1),this.role?o("q-tabs",{staticClass:"hidden",attrs:{color:"grey-10","underline-color":"primary"}},[o("q-tab",{attrs:{slot:"title",default:"",iconx:"fal fa-users fa-sm",label:"Current",name:"current"},slot:"title"}),o("q-tab",{attrs:{slot:"title",label:"Add",iconx:"fal fa-plus fa-sm",exact:"",name:"add"},slot:"title"}),o("q-tab-pane",{attrs:{name:"current"}}),o("q-tab-pane",{attrs:{name:"add"}})],1):e._e()],1)])},F=[];B._withStripped=!0;var Y={name:"ComponentAdminAccessRoles",props:{},data:function(){return{role:null,roleName:"",roles:[]}},methods:{clickRole:function(e,t){this.role=e,this.roleName=t},getRoles:function(e){var t=this;u.getRoles(function(o){t.roles=o,e&&(t.role=e)})}},computed:i()({},Object(c["c"])("user",["permissions"])),watch:{role:function(){var e=this;u.getRoleUsers(this.role,function(t){e.roleUsers=t})},ticked:function(){u.putRolePages(this.role,this.ticked)}},components:{},created:function(){this.getRoles()}},J=Y,G=(o("302e"),Object(g["a"])(J,B,F,!1,null,"3653ce6f",null));G.options.__file="users.vue";var H=G.exports,K={name:"PageAdminAccess",data:function(){return{accessTab:""}},components:{roles:U,structure:E,users:H}},Q=K,V=(o("ac49"),Object(g["a"])(Q,a,n,!1,null,null,null));V.options.__file="access.vue";t["default"]=V.exports},"95f5":function(e,t,o){},ac38:function(e,t,o){},ac49:function(e,t,o){"use strict";var a=o("95f5"),n=o.n(a);n.a},b805:function(e,t,o){"use strict";var a=o("2f7a"),n=o.n(a);n.a},c7fb:function(e,t,o){"use strict";var a=o("6a25"),n=o.n(a);n.a},cfb4:function(e,t,o){"use strict";var a=o("fd9f"),n=o.n(a);n.a},ddca:function(e,t,o){"use strict";var a=o("32d1"),n=o.n(a);n.a},fd9f:function(e,t,o){}}]);