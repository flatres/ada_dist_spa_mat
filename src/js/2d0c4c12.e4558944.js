(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["2d0c4c12"],{"3bd1":function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"row",staticStyle:{height:"100vh"}},[a("div",{staticClass:"col-4 q-px-md"},[a("h1",[t._v("Cats")]),a("crud",{ref:"crud",staticStyle:{max:"height:80vh"},attrs:{api:t.catApi,columns:t.catColumns,sortBy:"name",search:"",rowsPerPage:"1000"},on:{clickedRow:t.clickedCat}})],1),t._m(0),t._m(1)])},c=[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"col-4 q-px-md"},[a("h1",[t._v("Tags")])])},function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"col-4 q-px-md"},[a("h1",[t._v("Members")])])}],o=a("4778"),i={getAreas:function(t,e,a){o["a"].get("/watch/exgarde/areas ").then(function(e){t(e.data)}).catch(function(t){console.log(t)})},getArea:function(t,e,a){o["a"].get("/watch/exgarde/areas/"+a).then(function(e){t(e.data)}).catch(function(t){console.log(t)})},getLocations:function(t,e,a){o["a"].get("/watch/exgarde/locations").then(function(e){t(e.data)}).catch(function(t){console.log(t)})},getLocation:function(t,e,a){o["a"].get("/watch/exgarde/locations/"+a).then(function(e){t(e.data)}).catch(function(t){console.log(t)})},getLocationByDate:function(t,e,a){var n=a.date;o["a"].get("/watch/exgarde/locations/"+a.id+"/"+n).then(function(e){t(e.data)}).catch(function(t){console.log(t)})},getPeople:function(t,e,a){o["a"].get("/watch/exgarde/people").then(function(e){t(e.data)}).catch(function(t){console.log(t)})},getPerson:function(t,e,a){o["a"].get("/watch/exgarde/people/"+a).then(function(e){t(e.data)}).catch(function(t){console.log(t)})},getPersonByDate:function(t,e,a){var n=a.date;o["a"].get("/watch/exgarde/people/"+a.id+"/"+n).then(function(e){t(e.data)}).catch(function(t){console.log(t)})}},l={name:"PageLabEmail",data:function(){return{catApi:{get:i.getCats},area:null,areaColumns:[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"Name",label:"Name",field:"name",type:"string",align:"left"}]}},components:{}},s=l,r=a("2be6"),u=Object(r["a"])(s,n,c,!1,null,null,null);e["default"]=u.exports}}]);