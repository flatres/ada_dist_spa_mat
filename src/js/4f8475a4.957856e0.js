(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["4f8475a4"],{"01a0":function(t,n,e){"use strict";var a=e("c6e2"),o=e.n(a);o.a},"9fdc":function(t,n,e){"use strict";e.r(n);var a=function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("q-page",{staticClass:"no-scroll"},[e("q-table",{attrs:{data:t.students,columns:t.columns,filter:t.filter,separator:t.separator,pagination:t.paginationControl,"row-key":t.id,color:"primary",loading:t.loading,dark:"",dense:""},on:{"update:pagination":function(n){t.paginationControl=n}},scopedSlots:t._u([{key:"top-left",fn:function(n){return[e("q-input",{staticClass:"col-6",attrs:{type:"search","hide-underline":"",color:"secondary",dark:""},model:{value:t.filter,callback:function(n){t.filter=n},expression:"filter"}})]}},{key:"body",fn:function(n){return[e("q-tr",{attrs:{props:n}},[e("q-td",{key:"firstname",attrs:{props:n}},[e("span",[t._v(t._s(n.row.firstname))])]),e("q-td",{key:"lastname",attrs:{props:n}},[t._v(t._s(n.row.lastname))]),e("q-td",t._l(n.row.tags,function(n){return e("span",{key:n.id,staticClass:"tag"},[t._v("\n              "+t._s(n.name.substring(0,2))+"\n            ")])}),0)],1)]}}])})],1)},o=[],s=e("c0f5"),i={name:"ComponentStudentsList",components:{},data:function(){return{id:null,filter:"",selectedStudent:null,names:[],students:[],separator:"horizontal",selection:"none",pagination:{page:1},paginationControl:{rowsPerPage:40,page:1,sortBy:"txtInitialedName"},loading:!1,dark:!0,columns:[{name:"firstname",required:!0,label:"Name",align:"left",field:"firstname",sortable:!0},{name:"lastname",required:!0,label:"Name",align:"left",field:"lastname",sortable:!0}]}},computed:{},methods:{},mounted:function(){},created:function(){var t=this;this.loading=!0,s["a"].getStudentList(function(n){console.log(n),t.loading=!1,t.students=n})}},r=i,l=(e("01a0"),e("2be6")),c=Object(l["a"])(r,a,o,!1,null,"fa6e2784",null);n["default"]=c.exports},c0f5:function(t,n,e){"use strict";var a=e("4778");n["a"]={getStudentNames:function(t,n){a["a"].get("/students/names").then(function(n){t(n.data)}).catch(function(t){console.log(t)})},getStudentList:function(t,n){a["a"].get("/students/list").then(function(n){t(n.data)}).catch(function(t){console.log(t)})}}},c6e2:function(t,n,e){}}]);