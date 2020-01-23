(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[19],{1394:function(t,e,n){"use strict";var a=n("17a4"),o=n.n(a);o.a},"17a4":function(t,e,n){},7135:function(t,e,n){"use strict";var a=n("7a82"),o=n.n(a);o.a},"7a82":function(t,e,n){},"9de1":function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("toolbar-page",{attrs:{elements:t.elements,default:"students"}})},o=[],c=n("08e9"),s=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("q-tree",{staticClass:"q-mt-lg",attrs:{nodes:t.tree,color:"primary","default-expand-all":"","node-key":"id","label-key":"name",dark:""},scopedSlots:t._u([{key:"default-header",fn:function(e){return n("div",{on:{click:function(n){return t.clickNode(e.node)}}},[n("span",{staticClass:"text-weight-thin no-shadow"},[e.node.newCat||e.node.newTag?t._e():n("q-icon",{staticClass:"q-mt-xs q-mr-xs q-ml-sm",attrs:{name:"fal fa-tag",color:t.nodeColor(e.node)}}),e.node.newCat?n("new-button",{staticClass:"q-mt-xs q-mr-xs q-ml-sm",attrs:{color:t.nodeColor(e.node),success:t.newCat}}):t._e(),e.node.newTag?n("new-button",{staticClass:"q-mt-xs q-mr-xs q-ml-sm",attrs:{color:t.nodeColor(e.node),success:t.newTag}}):t._e()],1),n("span",[t._v("\n         "+t._s(e.node.name)+"\n       ")])])}}])})],1)},i=[],r=(n("c880"),n("4778")),d={getStudentTagTree:function(t,e,n){r["a"].get("/admin/tags/tree/students/"+n).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getTags:function(t,e,n){r["a"].get("/admin/tags").then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getCategories:function(t,e,n){r["a"].get("/admin/tags/categories").then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},postCategory:function(t,e,n){r["a"].post("/admin/tags/categories",{name:n}).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},postTag:function(t,e,n,a){r["a"].post("/admin/tags/tag",{catName:n,name:a}).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getTag:function(t,e,n){r["a"].get("/admin/tags/"+n.id).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},putTag:function(t,e,n){r["a"].put("/admin/tags",n).then((function(e){t(e.data)})).catch((function(t){console.log(t),e()}))},deleteTag:function(t,e,n){r["a"].delete("/admin/tags/"+n).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))}},l=n("ad81"),u={name:"AdminTagsStudents",data:function(){return{tree:[],currentNodeId:null,currentCatId:null}},methods:{process:function(t){console.log(t),this.tree=t.map((function(t){return t.tags.unshift({newTag:!0,name:"",id:"nt_"+t.id,catName:t.name,catId:t.id}),{id:"c"+t.id,name:t.name,children:t.tags,catId:t.id}})),this.tree.unshift({newCat:!0,name:"",id:"c0"}),console.log(this.tree)},newCat:function(t){if(0===t.length)return!1;d.postCategory(this.fetchTree,this.$errorHandler,t)},newTag:function(t){if(0===t.length)return!1;console.log(this.currentCatName),d.postTag(this.fetchTree,this.$errorHandler,this.currentCatName,t)},clickNode:function(t){console.log(t),t.catId&&(this.currentNodeId=t.id,this.currentCatId="c"+t.catId,t.catName.length>0&&(this.currentCatName=t.catName),console.log(this.currentCatName))},nodeColor:function(t){return t.catId?t.id===this.currentNodeId?"primary":"white":t.id===this.currentCatId?"primary":"white"},fetchTree:function(){d.getStudentTagTree(this.process,null,0)}},computed:{},components:{NewButton:l["a"]},created:function(){this.fetchTree()}},m=u,f=(n("7135"),n("2be6")),g=Object(f["a"])(m,s,i,!1,null,"41ba5b65",null),h=g.exports,p=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("crud",{ref:"crud",attrs:{data:t.data,api:t.api,columns:t.columns,search:"",canNew:"",canDelete:"",canEdit:"",inlineEdit:""}})],1)},C=[],w=n("d612"),T=n("89a2"),b={name:"ComponentCompanies",data:function(){return{api:{get:d.getTags,getSingle:d.getTag,put:d.putCompanies,post:d.postCompanies,delete:d.deleteCompanies},columns:[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"Name",label:"Name",field:"name",type:"string",align:"left",validations:{required:T["required"],minLength:Object(T["minLength"])(4)},editable:!0}],showForm:!0}},computed:{},components:{Crud:w["a"]},created:function(){}},N=b,q=(n("1394"),Object(f["a"])(N,p,C,!1,null,"d1c2763a",null)),_=q.exports,v={name:"PageAdminTags",data:function(){return{elements:[{name:"students",label:"Students",component:h,shortcut:"s"},{name:"staff",label:"Staff",component:_,shortcut:"t"}]}},components:{toolbarPage:c["a"]},methods:{refresh:function(){}}},x=v,I=Object(f["a"])(x,a,o,!1,null,null,null);e["default"]=I.exports}}]);