(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[10],{2070:function(e,t,n){},"246c":function(e,t,n){},"2bd6":function(e,t,n){},"2d85":function(e,t,n){},5894:function(e,t,n){},6027:function(e,t,n){"use strict";var o=n("8d6c"),s=n.n(o);s.a},"6a8a":function(e,t,n){"use strict";var o=n("5894"),s=n.n(o);s.a},8025:function(e,t,n){"use strict";var o=n("2070"),s=n.n(o);s.a},"8d6c":function(e,t,n){},aa4d:function(e,t,n){"use strict";var o=n("2d85"),s=n.n(o);s.a},b84d:function(e,t,n){"use strict";var o=n("246c"),s=n.n(o);s.a},d75e:function(e,t,n){"use strict";var o=n("2bd6"),s=n.n(o);s.a},e5bb:function(e,t,n){"use strict";n.r(t);var o=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("toolbar-page",{attrs:{elements:e.elements,default:"roles"}})},s=[],a=n("08e9"),c=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("crud",{ref:"crud",attrs:{data:e.data,api:e.api,columns:e.columns,search:"",canNew:"",canDelete:"",canEdit:"",inlineEdit:"",sidebarComponents:e.sidebarComponents,sidebarWidth:"40",indicator:"isDefault"}})],1)},l=[],i=n("d612"),r=n("4778"),u={getRoles:function(e){r["a"].get("/admin/access/roles").then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},getModuleTree:function(e){r["a"].get("/admin/access/module/tree").then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},getRolePages:function(e,t){r["a"].get("/admin/access/roles/pages/"+e).then((function(e){t(e.data)})).catch((function(e){console.log(e)}))},getRoleUsers:function(e,t,n){n.roleID&&r["a"].get("/admin/access/roles/users/"+n.roleID).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},getRoleNewUsers:function(e,t,n){n.roleID&&r["a"].get("/admin/access/roles/users/new/"+n.roleID).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},postRoleUsers:function(e,t,n,o){o.roleID&&r["a"].post("/admin/access/roles/users/"+o.roleID,n).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},deleteRoleUsers:function(e,t,n,o){r["a"].delete("/admin/access/roles/users/"+o.roleID+"/"+n).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},putRolePages:function(e,t){r["a"].put("/admin/access/roles/pages",{id:e,pages:t}).then((function(e){})).catch((function(e){console.log(e)}))},deleteRole:function(e,t,n){r["a"].delete("/admin/access/roles/"+n).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},deleteRoleUser:function(e,t,n){r["a"].delete("/admin/access/roles/user/"+e+"/"+t).then((function(e){n(e.data)})).catch((function(e){console.log(e)}))},postRole:function(e,t,n){r["a"].post("/admin/access/roles",n).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},putRole:function(e,t,n){r["a"].put("/admin/access/roles",n).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},postUser:function(e,t,n){r["a"].post("/admin/access/roles/user",{roleId:e,userId:t}).then((function(e){n(e.data)})).catch((function(e){console.log(e)}))},getAllUsers:function(e,t){r["a"].get("/admin/access/roles/allusers").then((function(e){t(e.data)})).catch((function(e){console.log(e)}))}},d=function(){var e=this,t=e.$createElement,n=e._self._c||t;return e.selectedRow?n("div",{staticClass:"row",staticStyle:{"max-height":"80vh","overflow-y":"scroll"}},[e.selectedRow.id&&e.tree?n("q-tree",{attrs:{nodes:e.tree,color:"accent","default-expand-all":"",ticked:e.ticked,"tick-strategy":e.tickStrategy,filter:e.tickFilter,"node-key":"id","label-key":"name",dark:""},on:{"update:ticked":function(t){e.ticked=t}}}):e._e()],1):e._e()},f=[],m={name:"ComponentCrudDetails",data:function(){return{tree:null,ticked:null,tickStrategy:"leaf-filtered",tickFilter:null,moduleIcon:{students:"fal fa-child",transport:"fal fa-space-shuttle"}}},methods:{getPages:function(){var e=this;u.getRolePages(this.roleID,(function(t){e.ticked=t}))}},computed:{roleID:function(){return this.selectedRow.id}},watch:{selectedRow:function(){console.log(this.selectedRow),this.getPages()},ticked:function(){u.putRolePages(this.roleID,this.ticked)}},components:{},created:function(){var e=this;u.getModuleTree((function(t){e.tree=t})),this.getPages()},props:{selectedRow:{required:!0}}},p=m,h=(n("6027"),n("2be6")),g=Object(h["a"])(p,d,f,!1,null,"04cbf286",null),b=g.exports,v=function(){var e=this,t=e.$createElement,n=e._self._c||t;return e.selectedRow?n("div",{staticClass:"row"},[n("crud",{ref:"crud",attrs:{api:e.api,columns:e.columns,search:"",canNew:"",canDelete:"",newComponent:e.newComponent,sortBy:"lastName"}})],1):e._e()},w=[],k=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("div",{staticClass:"row",staticStyle:{"min-height":"400px","min-width":"500px"}},[n("crud",{ref:"crud",staticClass:"col",attrs:{columns:e.columns,search:"",sortBy:"lastName",dataOverride:e.staffList,hidePagination:"",rowsPerPage:"10",indicator:"exists"},on:{selected:e.selected}})],1),n("div",{staticClass:"row q-mt-md full-width"},[n("q-btn",{directives:[{name:"hotkey",rawName:"v-hotkey.once",value:{enter:e.submit},expression:"{'enter': submit}",modifiers:{once:!0}}],staticClass:"text-primary col",attrs:{outline:"",dark:"",icon:"fal fa-check",label:"Create New"},nativeOn:{click:function(t){return e.submit(t)}}})],1)])},y=[],q=n("7f3a"),C=n.n(q),O={name:"ComponentLabCrudAdvancedNew",props:{rowKey:{default:"id"},data:{required:!0,type:Array},api:{required:!0,type:Object},newUsers:[]},data:function(){return{staffList:[],columns:[{name:"id",label:"",field:"string",hidden:!0},{name:"lastName",label:"Last Name",field:"lastname",type:"string",align:"left"},{name:"firstName",label:"First Name",field:"firstname",type:"string",align:"left"}],dark:!0}},methods:{submit:function(){u.postRoleUsers(this.success,this.error,this.newUsers,this.api.parameters)},success:function(e){var t;console.log(e),(t=this.data).push.apply(t,C()(e))},error:function(){this.$q.notify("Oops, something went wrong")},selected:function(e){this.newUsers=e}},computed:{},watch:{},components:{Crud:i["a"]},created:function(){var e=this;u.getRoleNewUsers((function(t){e.staffList=t}),null,this.api.parameters)}},R=O,x=(n("8025"),Object(h["a"])(R,k,y,!1,null,"b53ec866",null)),P=x.exports,D={name:"AdminAccessRolesUsers",data:function(){return{api:{get:u.getRoleUsers,parameters:{roleID:null},post:u.postRoleUsers,delete:u.deleteRoleUsers},columns:[{name:"id",label:"",field:"string",hidden:!0},{name:"lastName",label:"Last Name",field:"lastname",type:"string",align:"left"},{name:"firstName",label:"First Name",field:"firstname",type:"string",align:"left"}],newComponent:P}},methods:{},computed:{roleID:function(){return this.selectedRow.id}},watch:{selectedRow:function(){this.selectedRow.id&&(this.api={get:u.getRoleUsers,parameters:{roleID:this.selectedRow.id},put:u.putRole,post:u.postRole,delete:u.deleteRole})}},components:{Crud:i["a"]},created:function(){this.selectedRow.id&&(this.api={get:u.getRoleUsers,parameters:{roleID:this.selectedRow.id},post:u.postRoleUsers,delete:u.deleteRoleUsers})},props:{selectedRow:{required:!0}}},_=D,N=(n("d75e"),Object(h["a"])(_,v,w,!1,null,"0d3892fe",null)),j=N.exports,I=n("89a2"),M={name:"AdminAccessRoles",data:function(){return{api:{get:u.getRoles,put:u.putRole,post:u.postRole,delete:u.deleteRole},columns:[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"Name",label:"Name",field:"name",type:"string",align:"left",validations:{required:I["required"],minLength:Object(I["minLength"])(4)},editable:!0}],sidebarComponents:[{title:"Users",name:"Users",component:j},{title:"Pages",name:"Pages",component:b}],showForm:!0}},computed:{},components:{Crud:i["a"]},created:function(){}},$=M,U=(n("6a8a"),Object(h["a"])($,c,l,!1,null,"2635d7d9",null)),S=U.exports,T=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("q-page",{staticClass:"q-ml-xl"},[n("div",{staticClass:"row"},[n("div",{staticClass:"col q-mt-xl",staticStyle:{"overflow-y":"scroll","max-height":"80vh"}},[n("q-icon",{attrs:{name:"fal fa-plus-circle",color:"accent"},nativeOn:{click:function(t){return e.newModule(t)}}}),n("q-tree",{staticClass:"q-mt-lg",attrs:{nodes:e.tree,color:"accent","default-expand-all":"","node-key":"id","label-key":"name",dark:""},scopedSlots:e._u([{key:"default-header",fn:function(t){return n("div",{},[t.node.module?n("div",[n("span",{staticClass:"q-mx-sm"},[n("span",{staticClass:"inline-block text-center",staticStyle:{"min-width":"20px","min-height":"20px"}},[n("q-icon",{style:{color:t.node.color},attrs:{name:""===t.node.icon?"fal fa-empty-set":t.node.icon}})],1),n("q-popup-edit",{on:{save:function(n){return e.saveModuleIcon(t.node.moduleId,t.node.icon)}},model:{value:t.node.icon,callback:function(n){e.$set(t.node,"icon",n)},expression:"prop.node.icon"}},[n("q-field",[n("q-input",{model:{value:t.node.icon,callback:function(n){e.$set(t.node,"icon",n)},expression:"prop.node.icon"}})],1)],1)],1),n("span",[e._v("\n              "+e._s(t.node.name)+"\n              "),n("q-popup-edit",{attrs:{dark:""},on:{save:function(n){return e.saveModuleName(t.node.moduleId,t.node.name)}},model:{value:t.node.name,callback:function(n){e.$set(t.node,"name",n)},expression:"prop.node.name"}},[n("q-field",[n("q-input",{model:{value:t.node.name,callback:function(n){e.$set(t.node,"name",n)},expression:"prop.node.name"}})],1)],1)],1),n("span",{staticClass:"text-weight-thin no-shadow"},[n("q-btn",{staticClass:"no-shadow",staticStyle:{width:"20px"},attrs:{icon:"fal fa-palette fa-xs"},on:{click:function(n){return n.stopPropagation(),e.setColor(t.node.moduleId,t.node.name,t.node.color)}}}),n("q-icon",{staticClass:"q-mt-xs q-mr-xs q-ml-sm",attrs:{name:"fal fa-trash",color:"red"},nativeOn:{click:function(n){return e.deleteModule(t.node.moduleId)}}}),n("q-btn",{staticClass:"no-shadow",staticStyle:{width:"20px"},attrs:{icon:"fal fa-plus fa-xs"},on:{click:function(n){return n.stopPropagation(),e.newPage(t.node.moduleId)}}})],1)]):n("div",{},[n("span",{staticClass:"row text-accent"},[e._v("\n              "+e._s(t.node.name)+"\n              "),n("div",[n("q-icon",{staticClass:"q-mx-md",attrs:{name:"fa fa-edit",color:"accent"}}),n("q-popup-edit",{attrs:{dark:""},on:{save:function(n){return e.savePage(t.node.id,t.node.name)}},model:{value:t.node.name,callback:function(n){e.$set(t.node,"name",n)},expression:"prop.node.name"}},[n("q-field",[n("q-input",{model:{value:t.node.name,callback:function(n){e.$set(t.node,"name",n)},expression:"prop.node.name"}})],1)],1)],1),n("q-icon",{staticClass:"q-mt-xs q-mr-md",attrs:{name:"fal fa-trash",color:"red"},nativeOn:{click:function(n){return e.deletePage(t.node.id)}}}),n("q-icon",{staticClass:"q-mt-xs",attrs:{name:"fal fa-info-circle"}},[n("q-tooltip",{attrs:{anchor:"center right",self:"center left",offset:[10,10]}},[n("div",e._l(t.node.roles,(function(t){return n("p",{key:t.id,staticClass:"capitalize"},[e._v("\n                      "+e._s(t.name)+"\n                    ")])})),0)])],1)],1)])])}}])})],1)])])},A=[],E=(n("e125"),n("4823"),n("2e73"),n("dde3"),n("76d0"),n("0c1f"),n("8e9e")),L=n.n(E),F=n("9ce4"),z={deleteModule:function(e,t){r["a"].delete("/admin/access/structure/module/"+e).then((function(e){t(e.data)})).catch((function(e){console.log(e)}))},postModuleIcon:function(e,t,n){r["a"].post("/admin/access/structure/module/icon",{id:e,icon:t}).then((function(e){n(e.data)})).catch((function(e){console.log(e)}))},postModuleName:function(e,t,n){r["a"].post("/admin/access/structure/module/name",{id:e,name:t}).then((function(e){n(e.data)})).catch((function(e){console.log(e)}))},postModule:function(e,t){r["a"].post("/admin/access/structure/module",{name:e}).then((function(e){t(e.data)})).catch((function(e){console.log(e)}))},postPage:function(e,t,n){r["a"].post("/admin/access/structure/module/page",{moduleId:e,name:t}).then((function(e){n(e.data)})).catch((function(e){console.log(e)}))},putPage:function(e,t,n){r["a"].put("/admin/access/structure/module/page",{id:e,name:t}).then((function(e){n(e.data)})).catch((function(e){console.log(e)}))},deletePage:function(e,t){r["a"].delete("/admin/access/structure/module/page/"+e).then((function(e){t(e.data)})).catch((function(e){console.log(e)}))},postColor:function(e,t,n){r["a"].post("/admin/access/structure/module/color",{id:e,color:t}).then((function(e){n(e.data)})).catch((function(e){console.log(e)}))}},B=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("q-dialog",{attrs:{"stack-buttons":"","prevent-close":"",dark:""},scopedSlots:e._u([{key:"buttons",fn:function(t){return[n("q-btn",{attrs:{label:"Save Color",stylexx:"background:black",stylex:{color:e.currentColor}},on:{click:e.saveColor}}),n("q-btn",{attrs:{flat:"",label:"No thanks"},on:{click:e.cancelMe}})]}}]),model:{value:e.showDialog,callback:function(t){e.showDialog=t},expression:"showDialog"}},[n("span",{attrs:{slot:"title"},slot:"title"},[e._v("Choose Color")]),n("span",{staticClass:"capitalize",attrs:{slot:"message"},slot:"message"},[e._v(e._s(e.nameText))]),n("div",{staticClass:"no-scroll",attrs:{slot:"body"},slot:"body"},[n("q-color",{staticStyle:{"max-width":"300px"},attrs:{"format-model":"hex","no-parent-field":""},model:{value:e.currentColor,callback:function(t){e.currentColor=t},expression:"currentColor"}})],1)])},J=[],K=(n("c880"),{data:function(){return{}},props:["show","color","name"],computed:{showDialog:{get:function(){return this.show},set:function(e){this.$emit("update:show",!1)}},currentColor:{get:function(){return this.color},set:function(e){this.$emit("update:color",e)}},nameText:function(){return this.name}},methods:{cancelMe:function(){this.$emit("update:show",!1)},saveColor:function(){console.log(this.currentColor),this.$emit("saveColor",this.currentColor),this.$emit("update:show",!1)}},created:function(){}}),Y=K,W=Object(h["a"])(Y,B,J,!1,null,null,null),G=W.exports;function H(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,o)}return n}function Q(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?H(Object(n),!0).forEach((function(t){L()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):H(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var V={name:"ComponentAdminAccessRoles",props:{},data:function(){return{showColorPicker:!1,tree:[],ticked:[],tickStrategy:"leaf-filtered",tickFilter:null,role:null,roles:[],moduleIcon:{students:"fal fa-child",transport:"fal fa-space-shuttle"},colorModuleId:null,colorModulecolor:null}},methods:{saveColor:function(e){var t=this;z.postColor(this.colorModuleId,this.colorModuleColor,(function(){t.getTree()}))},setColor:function(e,t,n){this.colorModuleColor=n,this.colorModuleName=t,this.colorModuleId=e,this.showColorPicker=!0},deleteModule:function(e){var t=this;this.$q.dialog({title:"Confirm",message:"Delete this Module? The consequences of an error are grave.",ok:"Yes",cancel:"NO!!!"}).onOK((function(){z.deleteModule(e,(function(){t.$q.notify("Deleted"),t.getTree()}))})).onDismiss((function(){t.$q.notify("Cancelled")}))},getTree:function(){var e=this;u.getModuleTree((function(t){console.log(t),e.tree=t}))},saveModuleIcon:function(e,t){var n=this;z.postModuleIcon(e,t,(function(){n.getTree()}))},saveModuleName:function(e,t){var n=this;z.postModuleName(e,t,(function(){n.getTree()}))},newModule:function(){var e=this;this.$q.dialog({title:"New Module",message:"Name?",prompt:{model:"",type:"text"},cancel:!0,color:"accent"}).onOk((function(t){z.postModule(t,(function(){e.getTree()}))})).onDismiss((function(){e.$q.notify("Cancelled")}))},newPage:function(e){var t=this;this.$q.dialog({title:"New Page",message:"Name?",prompt:{model:"",type:"text"},cancel:!0,color:"accent"}).onOk((function(n){console.log("ok"),z.postPage(e,n,(function(){t.getTree()}))})).onDismiss((function(){t.$q.notify("Cancelled")}))},savePage:function(e,t){var n=this;z.putPage(e,t,(function(){n.getTree()}))},deletePage:function(e){var t=this;this.$q.dialog({title:"Confirm",message:"Delete this Page? The consequences of an error are grave.",ok:"Yes",cancel:"NO!!!"}).onOk((function(){z.deletePage(e,(function(){t.$q.notify("Deleted"),t.getTree()}))})).onDismiss((function(){t.$q.notify("Cancelled")}))}},computed:Q({},Object(F["c"])("user",["permissions"]),{test:function(){return console.log(this.showColorPicker),this.showColorPicker}}),watch:{ticked:function(){console.log(this.ticked)},role:function(){}},components:{colorPicker:G},created:function(){var e=this;u.getRoles((function(t){e.roles=t})),this.getTree()}},X=V,Z=(n("b84d"),Object(h["a"])(X,T,A,!1,null,"75011780",null)),ee=Z.exports,te=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("q-page",{staticClass:" row"},[n("div",{staticClass:"col-3 q-mr-md"},[n("q-list",{attrs:{dark:"",separator:""}},[n("q-item-label",{staticClass:"row q-pt-sm",staticStyle:{"border-bottom":"1px solid grey",height:"50px"},attrs:{header:""}},[n("span",{staticClass:"col"},[e._v("Roles")])]),e._l(e.roles,(function(t){return n("q-item",{key:t.value,class:{"bg-dark":t.value==e.role},nativeOn:{click:function(n){return e.clickRole(t.value,t.label)}}},[n("q-item-section",{staticClass:"capitalize",attrs:{label:t.label}}),n("q-item-side",{attrs:{right:""}},[n("q-btn",{attrs:{flat:"",round:"",dense:"",icon:"more_vert"}},[n("q-menu",[n("q-list",{attrs:{link:""}},[n("q-item",{directives:[{name:"close-overlay",rawName:"v-close-overlay"}]},[n("q-item-label",[e._v("Rename")])],1),n("q-item",{directives:[{name:"close-overlay",rawName:"v-close-overlay"}],nativeOn:{click:function(n){return e.deleteRole(t.value)}}},[n("q-item-label",[e._v("Delete")])],1)],1)],1)],1)],1)],1)}))],2)],1),n("div",{staticClass:"col"},[n("q-btn-group",[n("q-btn",{attrs:{color:"dark",label:"users",icon:"timeline"}}),n("q-btn",{attrs:{color:"dark",icon:"visibility"}}),n("q-btn",{attrs:{color:"dark",icon:"update"}})],1),this.role?n("q-tabs",{staticClass:"hidden",attrs:{color:"grey-10","underline-color":"accent"}},[n("q-tab",{attrs:{slot:"title",default:"",iconx:"fal fa-users fa-sm",label:"Current",name:"current"},slot:"title"}),n("q-tab",{attrs:{slot:"title",label:"Add",iconx:"fal fa-plus fa-sm",exact:"",name:"add"},slot:"title"}),n("q-tab-panel",{attrs:{name:"current"}}),n("q-tab-panel",{attrs:{name:"add"}})],1):e._e()],1)])},ne=[];function oe(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,o)}return n}function se(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?oe(Object(n),!0).forEach((function(t){L()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):oe(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var ae={name:"ComponentAdminAccessRoles",props:{},data:function(){return{role:null,roleName:"",roles:[]}},methods:{clickRole:function(e,t){this.role=e,this.roleName=t},getRoles:function(e){var t=this;u.getRoles((function(n){t.roles=n,e&&(t.role=e)}))}},computed:se({},Object(F["c"])("user",["permissions"])),watch:{role:function(){var e=this;u.getRoleUsers(this.role,(function(t){e.roleUsers=t}))},ticked:function(){u.putRolePages(this.role,this.ticked)}},components:{},created:function(){this.getRoles()}},ce=ae,le=(n("aa4d"),Object(h["a"])(ce,te,ne,!1,null,"03444790",null)),ie=le.exports,re={name:"PageAdminAccess",data:function(){return{elements:[{name:"roles",label:"Roles",component:S,shortcut:"r"},{name:"structure",label:"Structure",component:ee,shortcut:"s"},{name:"users",label:"Users",component:ie,shortcut:"u"}]}},components:{toolbarPage:a["a"]},methods:{refresh:function(){}}},ue=re,de=Object(h["a"])(ue,o,s,!1,null,null,null);t["default"]=de.exports}}]);