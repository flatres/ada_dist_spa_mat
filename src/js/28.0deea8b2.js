(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[28],{"7aa3":function(t,e,s){},bf8b:function(t,e,s){"use strict";var a=s("7aa3"),r=s.n(a);r.a},e4b7:function(t,e,s){},f0d1:function(t,e,s){"use strict";s.r(e);var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return t.getGlobalSubject?s("div",[s("div",{staticClass:"full-width bg-secondary",staticStyle:{height:"1px"}}),s("toolbar-page",{staticClass:"t-bar",attrs:{elements:t.elements,default:"wyap"},scopedSlots:t._u([{key:"before",fn:function(){return[s("year-bar",{staticClass:"yb-border",attrs:{exams:""}})]},proxy:!0}],null,!1,311916022)})],1):t._e()},r=[],i=(s("e125"),s("4823"),s("2e73"),s("76d0"),s("7f3a")),n=s.n(i),o=s("8e9e"),c=s.n(o),l=s("9ce4"),d=s("aba1"),u=s("89cf"),h=s("08e9"),p=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("loading",{attrs:{loading:t.loading,channel:"hod.metrics.wyaps"}}),t.loading?t._e():s("div",{staticClass:"row no-wrap q-px-xs q-pt-none text-left",staticStyle:{"padding-bottom":"200px"}},[s("div",{staticClass:"q-mr-md",staticStyle:{"min-width":"200px"}},[s("q-scroll-area",{staticClass:"bg-tertiary",staticStyle:{height:"80vh"}},[s("q-list",{},[s("q-item",{staticClass:"text-font-secondary",attrs:{dense:"",clickable:""},on:{click:function(e){t.newOpen=!0}}},[s("q-item-section",{attrs:{avatar:""}},[s("q-icon",{attrs:{name:"fas fa-plus",size:"xs",color:"positive"}})],1),s("q-item-section",[s("q-item-label",[t._v("Create New")])],1)],1),s("q-separator",{attrs:{color:"text-primary"}}),t._l(t.wyaps,(function(e){return s("div",{key:e.id},[s("q-item",{staticClass:"q-pa-xs text-white bg-tertiary q-my-xs",class:e.id===t.selectedWyapId?"active-class":"",attrs:{clickable:"",group:"wyaps"},on:{click:function(s){return t.setWyap(e.id)}}},[s("q-item-section",[s("q-item-label",{staticClass:"q-pl-sm text-font"},[t._v("\n                    "+t._s(e.name)+"\n                  ")])],1),s("q-item-section",{attrs:{side:""}},[s("q-icon",{attrs:{name:"fas fa-chevron-right",color:"font-secondary",size:"xs"}})],1)],1)],1)}))],2)],1)],1),s("div",{staticClass:"col no-scroll"},[t.wyap?s("q-toolbar",{staticClass:"bg-tertiary text-white no-padding",staticStyle:{"max-height":"38px","min-height":"38px","border-bottom":"1px solid black"},attrs:{shrink:"",dense:""}},[s("q-tabs",{attrs:{stretch:"","active-color":"accent",dense:""},model:{value:t.wyapTab,callback:function(e){t.wyapTab=e},expression:"wyapTab"}},[s("q-tab",{attrs:{name:"results",label:"results"}}),s("q-tab",{attrs:{name:"analysis",label:"analysis"}})],1),s("q-btn",{staticClass:"q-ml-md",attrs:{flat:"",label:"update",color:"font-secondary",dense:""},on:{click:t.openUpdate}})],1):t._e(),t.wyap?s("q-tab-panels",{staticStyle:{height:"80vh"},attrs:{animated:""},model:{value:t.wyapTab,callback:function(e){t.wyapTab=e},expression:"wyapTab"}},[s("q-tab-panel",{staticClass:"q-px-none",attrs:{name:"results"}},[s("results",{attrs:{results:t.results,wyap:t.wyap}})],1),s("q-tab-panel",{attrs:{name:"analysis"}},[s("div",{staticClass:"text-h6"},[t._v("Data Visualisation")]),t._v("\n          Coming soon ...\n        ")]),s("q-tab-panel",{staticClass:"q-px-none fit",attrs:{name:"update",stylex:"height:60vh"}},[s("update",{attrs:{results:t.results}})],1)],1):t._e()],1)]),s("q-dialog",{attrs:{persistent:""},model:{value:t.newOpen,callback:function(e){t.newOpen=e},expression:"newOpen"}},[s("q-card",{staticClass:"bd-font-secondary bg-secondary",staticStyle:{"min-width":"300px"}},[s("q-card-section",{staticClass:"row items-center bg-tertiary"},[s("div",{staticClass:"text-h7 text-font-secondary"},[t._v("Create New Wyap")])]),s("q-separator"),s("q-card-section",{staticClass:"row items-center"},[s("q-input",{staticClass:"full-width",attrs:{color:"font-secondary",label:"Name","stacked-label":""},model:{value:t.newWyap.name,callback:function(e){t.$set(t.newWyap,"name",e)},expression:"newWyap.name"}}),s("q-input",{staticClass:"full-width",attrs:{color:"font-secondary",label:"Out of how many marks?","stacked-label":"",type:"number"},model:{value:t.newWyap.marks,callback:function(e){t.$set(t.newWyap,"marks",e)},expression:"newWyap.marks"}})],1),s("q-card-actions",{attrs:{align:"right"}},[s("q-btn",{directives:[{name:"close-popup",rawName:"v-close-popup"}],attrs:{flat:"",label:"Cancel",color:"font"}}),s("q-btn",{directives:[{name:"close-popup",rawName:"v-close-popup"}],attrs:{disable:0===t.newWyap.name.length||!t.newWyap.marks,flat:"",label:"CREATE",color:"accent"},on:{click:t.createNew}})],1)],1)],1),t.wyap?s("q-dialog",{key:t.key,staticStyle:{height:"100vh"},attrs:{persistent:"",maximized:t.maximizedToggle,"transition-show":"slide-up","transition-hide":"slide-down"},model:{value:t.updateOpen,callback:function(e){t.updateOpen=e},expression:"updateOpen"}},[s("q-card",{staticClass:"bg-primary bd-font-secondary text-white column",staticStyle:{"overflow-y":"hidden","min-width":"500px",height:"90vh"}},[s("update",{staticClass:"col",attrs:{results:t.results,id:t.wyap.id,name:t.wyap.name,marks:t.wyap.marks},on:{close:function(e){t.updateOpen=!1},refresh:t.refresh}})],1)],1):t._e()],1)},b=[],m=s("47783"),y=s("452d"),f={getWyaps(t,e,s,a){var r=y["a"].getters["user/getGlobalSubject"];m["a"].get("/hod/"+r.id+"/wyaps/"+s+"/"+a).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},postWyap(t,e,s,a,r){var i=y["a"].getters["user/getGlobalSubject"];m["a"].post("/hod/"+i.id+"/wyaps/"+s+"/"+a,r).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},putWyap(t,e,s){var a=y["a"].getters["user/getGlobalSubject"];m["a"].put("/hod/"+a.id+"/wyaps/"+s.id,s).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},deleteWyap(t,e,s){var a=y["a"].getters["user/getGlobalSubject"];m["a"].put("/hod/"+a.id+"/wyaps/"+s.id).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getWyapResults(t,e,s){var a=y["a"].getters["user/getGlobalSubject"];m["a"].get("/hod/"+a.id+"/wyaps/"+s).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))}},g=s("10ac"),v=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"q-pr-sm",staticStyle:{"overflow-y":"scroll"}},[s("div",{staticClass:"row fit"},[s("div",{staticClass:"col q-mt-md"},[s("q-scroll-area",{staticClass:"column",staticStyle:{height:"75vh"}},[s("q-markup-table",{staticClass:"q-mb-xl",attrs:{dense:"",flat:"",bordered:""}},[s("thead",{staticClass:"q-mb-md"},[s("tr",{staticClass:"bg-tertiary"},[s("th",{staticClass:"text-left",on:{click:function(e){return t.setSort("name")}}},[t._v("Name")]),s("th",{staticClass:"text-left",on:{click:function(e){return t.setSort("boarding")}}},[t._v("Hs")]),s("th",{staticClass:"text-left",on:{click:function(e){return t.setSort("classCode")}}},[t._v("Class")]),s("th",{staticClass:"text-left",on:{click:function(e){return t.setSort("mark")}}},[t._v("Mark / "+t._s(t.wyap.marks))]),s("th",{staticClass:"text-left",on:{click:function(e){return t.setSort("percentage")}}},[t._v("%")]),s("th",{staticClass:"text-left",on:{click:function(e){return t.setSort("rank")}}},[t._v("Rank")]),s("th",{staticClass:"text-left",on:{click:function(e){return t.setSort("flag")}}},[t._v("Flag")]),s("th")])]),s("tbody",{on:{onpaste:t.paste}},t._l(t.sorted,(function(e){return s("tr",{key:e.id,class:t.rowClass(e),on:{onpaste:t.paste}},[s("td",{staticClass:"text-left"},[t._v(t._s(e.lastName+",  "+e.preName))]),s("td",{staticClass:"text-left"},[t._v(t._s(e.boarding))]),s("td",{staticClass:"text-left"},[t._v(t._s(e.classCode))]),s("td",{staticClass:"text-left"},[t._v(t._s(e.mark))]),s("td",{staticClass:"text-left"},[t._v(t._s(e.percentage))]),s("td",{staticClass:"text-left"},[t._v(t._s(e.rank))]),s("td",{staticClass:"text-left"}),s("td",{staticClass:"text-left"})])})),0)])],1)],1)])])},x=[],w=(s("632c"),{name:"hods.metrics.wyaps.results",props:{results:{type:Array,required:!0},wyap:{type:Object,required:!0}},data(){return{students:[],search:"",loading:!0,dates:[],sortKey:"lastName",sortDir:"asc"}},methods:{setSort(t){t===this.sortKey&&(this.sortDir="asc"===this.sortDir?"desc":"asc"),this.sortKey=t},paste(t){},rowClass(t){var e=0;"classCode"===this.sortKey&&(e=this.classes.indexOf(t.classCode)),"boarding"===this.sortKey&&(e=this.boarding.indexOf(t.boarding));var s=e%2===1?"bg-tertiary":"";return s}},computed:{visible(){return this.results},sorted(){var t=this.visible.filter((function(t){return t.id>0}));return t.sort(this.$compare(this.sortKey,this.sortDir))},classes(){return this.$parseOptions(this.sorted,"classCode").map((function(t){return t.value}))},boarding(){return this.$parseOptions(this.sorted,"boarding").map((function(t){return t.value}))}},watch:{results(){}},components:{},created(){}}),C=w,k=s("2be6"),q=s("bc74"),_=s("26a8"),S=s("2e0b"),O=s("e279"),W=s.n(O),j=Object(k["a"])(C,v,x,!1,null,"0bc28f48",null),$=j.exports;W()(j,"components",{QInput:q["a"],QScrollArea:_["a"],QMarkupTable:S["a"]});var Q=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"q-pa-none full-width column",staticStyle:{"overflow-y":"hidden"}},[s("q-bar",{staticClass:"bg-tertiary bd-font-secondary",staticStyle:{height:"50px"}},[s("q-input",{staticClass:"full-width bg-tertiary text-center q-pl-sm",staticStyle:{"font-size":"13px","max-height":"39px"},attrs:{size:"xs",color:"font-secondary","hide-bottom-space":"",borderless:""},on:{input:t.changed},model:{value:t.newName,callback:function(e){t.newName=e},expression:"newName"}}),s("q-space"),s("q-input",{staticClass:"full-width bg-tertiary text-center q-pl-sm",staticStyle:{"font-size":"13px","max-height":"39px","max-width":"80px"},attrs:{size:"xs",label:"marks /",color:"font-secondary","hide-bottom-space":"",borderless:""},on:{input:t.changed},model:{value:t.newMarks,callback:function(e){t.newMarks=e},expression:"newMarks"}})],1),t.hasErrors?s("q-banner",{staticClass:"text-font-negative bg-warning q-py-xs",attrs:{"inline-actions":""}},[s("ul",t._l(t.errors,(function(e,a){return s("li",{key:a},[t._v(t._s(e))])})),0)]):t._e(),s("q-scroll-area",{staticClass:"col"},[s("q-markup-table",{staticClass:"q-mb-xl",staticStyle:{"overflow-y":"hidden"},attrs:{dense:"",flat:"",bordered:""}},[s("thead",{staticClass:"q-mb-md"},[s("tr",{staticClass:"bg-tertiary"},[s("th"),s("th",{staticClass:"text-left",on:{click:function(e){return t.setSort("classCode")}}},[t._v("Class")]),s("th",{staticClass:"text-left",on:{click:function(e){return t.setSort("name")}}},[t._v("Name")]),s("th",{staticClass:"text-left",on:{click:function(e){return t.setSort("mark")}}},[t._v("Mark")]),s("th",{staticClass:"text-center",on:{click:function(e){return t.setSort("hasUnderPerformed")}}},[t._v("U/P")]),s("th"),s("th")])]),s("tbody",t._l(t.sorted,(function(e,a){return s("tr",{key:e.id,staticClass:"q-py-lg",class:t.rowClass(e)},[s("td",[t._v(t._s(a+1))]),s("td",{staticClass:"text-left"},[t._v(t._s(e.classCode))]),s("td",{staticClass:"text-left"},[t._v(t._s(e.lastName+",  "+e.preName))]),s("td",{staticClass:"text-left ada-mark"},[s("q-input",{staticClass:"full-width text-center q-pl-sm",class:t.inputClass(e),staticStyle:{"font-size":"13px","min-width":"60px","max-width":"60px","max-height":"25px"},attrs:{size:"xs",color:"font-secondary","hide-bottom-space":"",borderless:""},on:{paste:function(e){return t.onPaste(a,e)},input:t.changed},model:{value:e.mark,callback:function(s){t.$set(e,"mark",t._n(s))},expression:"s.mark"}})],1),s("td",{staticClass:"text-center"},[s("q-toggle",{attrs:{tabindex:"-1",dense:"","checked-icon":"check",color:"warning","unchecked-icon":"clear","true-value":1,"false-value":0},on:{input:function(e){t.hasChanged=!0}},model:{value:e.hasUnderperformed,callback:function(s){t.$set(e,"hasUnderperformed",s)},expression:"s.hasUnderperformed"}})],1),s("td"),s("td",{staticClass:"text-left"})])})),0)])],1),s("q-bar",{staticClass:"bg-tertiary bd-font-secondary",staticStyle:{height:"60px"}},[s("q-btn",{attrs:{color:"warning",label:"delete wyap",flat:"",size:"md"}}),s("q-space"),s("q-btn",{attrs:{label:"close",flat:"",size:"md"},on:{click:t.close}}),s("q-btn",{staticClass:"text-font-negative",attrs:{disable:!t.hasChanged,label:"save",color:"positive",size:"md"},on:{click:t.save}})],1),s("q-dialog",{attrs:{persistent:""},model:{value:t.closeConfirm,callback:function(e){t.closeConfirm=e},expression:"closeConfirm"}},[s("q-card",{staticClass:"bd-font-secondary bg-tertiary"},[s("q-card-section",{staticClass:"row items-center"},[s("span",{staticClass:"q-ml-sm"},[t._v("Sure about this? You have unsaved changes.")])]),s("q-card-actions",{attrs:{align:"right"}},[s("q-btn",{directives:[{name:"close-popup",rawName:"v-close-popup"}],attrs:{flat:"",label:"Close",color:"font"},on:{click:function(e){return t.$emit("close")}}}),s("q-btn",{directives:[{name:"close-popup",rawName:"v-close-popup"}],staticClass:"text-font-negative",attrs:{label:"Save",color:"positive"},on:{click:t.save}})],1)],1)],1)],1)},D=[],E=(s("c880"),s("4fb0"),{name:"hods.metrics.wyaps.results",props:{results:{type:Array,required:!0},name:{type:String,required:!0},marks:{type:Number,required:!0},id:{type:Number,required:!0}},data(){return{students:[],search:"",loading:!0,dates:[],sortKey:"lastName",sortDir:"asc",newResults:[],newName:null,newMarks:null,hasChanged:!1,closeConfirm:!1,hasErrors:!1,errors:[]}},methods:{setSort(t){t===this.sortKey&&(this.sortDir="asc"===this.sortDir?"desc":"asc"),this.sortKey=t},onPaste(t,e){e.stopPropagation(),e.preventDefault();var s,a=e.clipboardData.getData("text/plain"),r=a.split("\n");for(s=0;s<r.length;s++)this.sorted[s+t].mark=r[s];return!1},close(){this.hasChanged?this.closeConfirm=!0:this.$emit("close")},verify(){var t=this;this.errors=[],this.hasErrors=!1,0===this.newName.length&&(this.hasErrors=!0,this.errors.push("The title cannot be blank.")),parseInt(this.newMarks)||(this.hasErrors=!0,this.errors.push("The marks make no sense"));var e=this.newResults.filter((function(e){return t.markError(e.mark)}));return e.length>0&&(this.hasErrors=!0,this.errors.push("Marks must be either a number or blank")),0===this.errors.length},save(){var t=this;if(this.verify()){var e={id:this.id,name:this.newName,marks:this.newMarks,results:this.newResults};f.putWyap((function(e){t.$emit("refresh")}),this.$errorHandler,e)}},markError(t){if(t)return"number"!==typeof t},changed(){this.hasChanged=!0,this.verify()},rowClass(t){var e=0;"classCode"===this.sortKey&&(e=this.classes.indexOf(t.classCode)),"boarding"===this.sortKey&&(e=this.boarding.indexOf(t.boarding));var s=e%2===1?"bg-tertiary":"";return s},inputClass(t){var e=0;"classCode"===this.sortKey&&(e=this.classes.indexOf(t.classCode)),"boarding"===this.sortKey&&(e=this.boarding.indexOf(t.boarding));var s=e%2===1?"bg-tertiary":"bg-secondary";return s}},computed:{visible(){return this.newResults},sorted(){var t=this.visible.filter((function(t){return t.id>0}));return t.sort(this.$compare(this.sortKey,this.sortDir))},classes(){return this.$parseOptions(this.sorted,"classCode").map((function(t){return t.value}))},boarding(){return this.$parseOptions(this.sorted,"boarding").map((function(t){return t.value}))}},watch:{},components:{},created(){this.newResults=this.results.filter((function(t){return t.id>0})),this.newName=this.name,this.newMarks=this.marks}}),T=E,M=s("5d16"),N=s("f85a"),A=s("f962c"),P=s("01a4"),G=s("3d3c"),H=s("2ef0"),Y=s("e81c"),I=s("ebe6"),z=s("965d"),K=s("5b32"),L=s("58c0"),B=Object(k["a"])(T,Q,D,!1,null,null,null),R=B.exports;W()(B,"components",{QBar:M["a"],QToolbarTitle:N["a"],QInput:q["a"],QSpace:A["a"],QBanner:P["a"],QScrollArea:_["a"],QMarkupTable:S["a"],QToggle:G["a"],QBtn:H["a"],QDialog:Y["a"],QCard:I["a"],QCardSection:z["a"],QCardActions:K["a"]}),W()(B,"directives",{ClosePopup:L["a"]});var F={name:"HOD.Data.Wyaps",props:{},data(){return{loading:!1,wyaps:[],newOpen:!1,newWyap:{name:"",marks:null},selectedWyapId:null,wyapTab:"results",results:[],maximizedToggle:!1,updateOpen:!1,key:1}},methods:{process(t){this.loading=!1,this.wyaps=t},getWyaps(){this.year=this.$store.getters["hod/activeYear"],this.exam=this.$store.getters["hod/activeExam"],this.loading=!0,f.getWyaps(this.process,this.$errorHandler,this.year,this.exam)},createNew(){this.year=this.$store.getters["hod/activeYear"],this.exam=this.$store.getters["hod/activeExam"],f.postWyap(this.processNew,this.$errorHandler,this.year,this.exam,this.newWyap)},processNew(){this.getWyaps(),this.newWyap={name:"",marks:null}},processResults(t){this.results=t.results,this.$q.loading.hide()},setWyap(t){this.selectedWyapId=t},openUpdate(){this.key++,this.updateOpen=!0},getWyap(t){this.$q.loading.show(),f.getWyapResults(this.processResults,this.$errorHandler,t)},refresh(){this.getWyaps(),this.getWyap(this.selectedWyapId),this.updateOpen=!1}},computed:{columns(){var t=[];return t},wyap(){var t=this,e=this.wyaps.find((function(e){return e.id===t.selectedWyapId}));return e}},watch:{selectedWyapId(t){this.getWyap(t)}},components:{Loading:g["a"],Results:$,Update:R},created(){var t=this;this.subject=this.$store.getters["user/getGlobalSubject"],this.getWyaps(),this.subjectWatch=this.$store.watch((function(){return t.$store.getters["user/getGlobalSubject"]}),this.getWyaps),this.yearWatch=this.$store.watch((function(){return t.$store.getters["hod/activeYear"]}),this.getWyaps),this.examWatch=this.$store.watch((function(){return t.$store.getters["hod/activeExam"]}),this.getWyaps)},beforeDestroy(){this.subjectWatch&&this.subjectWatch(),this.yearWatch&&this.yearWatch(),this.examWatch&&this.examWatch()}},U=F,J=(s("bf8b"),s("6c93")),X=s("ac9b"),V=s("66dc"),Z=s("34ff"),tt=s("7d9a"),et=s("96f0"),st=s("8c18"),at=s("eb05"),rt=s("4776"),it=s("dd08"),nt=s("1411"),ot=s("1d98"),ct=Object(k["a"])(U,p,b,!1,null,null,null),lt=ct.exports;W()(ct,"components",{QScrollArea:_["a"],QList:J["a"],QItem:X["a"],QItemSection:V["a"],QIcon:Z["a"],QItemLabel:tt["a"],QSeparator:et["a"],QPopupProxy:st["a"],QBanner:P["a"],QToolbar:at["a"],QTabs:rt["a"],QTab:it["a"],QBtn:H["a"],QTabPanels:nt["a"],QTabPanel:ot["a"],QDialog:Y["a"],QCard:I["a"],QCardSection:z["a"],QInput:q["a"],QCardActions:K["a"]}),W()(ct,"directives",{ClosePopup:L["a"]});var dt=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("loading",{attrs:{loading:t.loading,channel:"hod.metrics.history"}}),t.loading?t._e():s("history-table",{key:t.componentKey,attrs:{history:t.subject.history,grades:t.subject.grades,bands:t.subject.bands,bandedHistory:t.subject.bandedHistory}}),t.loading?t._e():s("history-stack",{key:t.componentKey,ref:"stack",attrs:{id:"1",profile:t.subject.stackedHistory,grades:t.subject.grades}})],1)},ut=[],ht={getClassMetrics(t,e,s){var a=y["a"].getters["user/getGlobalSubject"];m["a"].get("/hod/"+a.id+"/metrics/class/"+s).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getYearMLO(t,e,s,a,r){var i=y["a"].getters["user/getGlobalSubject"];m["a"].get("/hod/"+i.id+"/metrics/year/"+s+"/MLO/"+a,{cancelToken:r}).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getYearMetrics(t,e,s,a,r){var i=y["a"].getters["user/getGlobalSubject"];m["a"].get("/hod/"+i.id+"/metrics/year/"+s+"/metrics/"+a,{cancelToken:r}).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getYearMetricsSpreadsheet(t,e,s,a,r){var i=y["a"].getters["user/getGlobalSubject"];m["a"].get("/hod/"+i.id+"/metrics/year/"+s+"/metrics/"+a+"/spreadsheet",{cancelToken:r}).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getExamHistory(t,e,s,a){var r=y["a"].getters["user/getGlobalSubject"];m["a"].get("/hod/"+r.id+"/metrics/year/"+s+"/history/"+a).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))}},pt=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("div",{staticClass:"row"},[s("q-btn-toggle",{staticClass:"col q-ml-xs",attrs:{spread:"","no-caps":"",dense:"",outline:"","toggle-color":"tertiary",color:"secondary","text-color":"font-secondary","toggle-text-color":"accent",options:t.styles,year:"full-width"},model:{value:t.style,callback:function(e){t.style=e},expression:"style"}}),s("q-btn-toggle",{staticClass:"col q-ml-xs",attrs:{outline:"",spread:"","no-caps":"",dense:"","toggle-color":"tertiary",color:"secondary","text-color":"font-secondary","toggle-text-color":"accent",options:t.modes,year:"full-width"},model:{value:t.mode,callback:function(e){t.mode=e},expression:"mode"}})],1),"linear"===t.style?s("q-markup-table",{staticClass:"q-mt-xs q-my-md",attrs:{dense:"",flat:"",bordered:""}},[s("thead",{staticClass:"q-mb-md"},[s("tr",{staticClass:"bg-tertiary"},[s("th",{staticClass:"text-right"},[t._v(t._s("percentage"===t.mode?"%":"Abs"))]),t._l(t.grades,(function(e){return s("th",{key:e.grade},[t._v("\n          "+t._s(e.grade)+"\n          "),"GCSE Avg"===e.grade?s("q-icon",{attrs:{size:"xs",color:"font",name:"fad fa-question-circle"}},[s("q-tooltip",{attrs:{"content-class":"bg-tertiary"}},[t._v("\n              The average GCSE grades of the pupils taking the subject in a given year.\n            ")])],1):t._e()],1)})),s("th")],2)]),s("tbody",t._l(t.history,(function(e){return s("tr",{key:e.year},[s("td",{staticClass:"text-right"},[t._v(t._s(e.year))]),t._l(t.grades,(function(a){return s("td",{key:a.grade,staticClass:"text-center"},[t._v("\n          "+t._s(t.getGrade(a.grade,e))+"\n        ")])})),s("td")],2)})),0)]):t._e(),"banded"===t.style?s("q-markup-table",{staticClass:"q-mt-xs q-my-md",attrs:{dense:"",flat:"",bordered:""}},[s("thead",{staticClass:"q-mb-md"},[s("tr",{staticClass:"bg-tertiary"},[s("th",{staticClass:"text-right"},[t._v(t._s("percentage"===t.mode?"%":"Abs"))]),t._l(t.bands,(function(e){return s("th",{key:e},[t._v("\n          "+t._s(e)+"\n          "),"GCSE Avg"===e?s("q-icon",{attrs:{size:"xs",color:"font",name:"fad fa-question-circle"}},[s("q-tooltip",{attrs:{"content-class":"bg-tertiary"}},[t._v("\n              The average GCSE grades of the pupils taking the subject in a given year.\n            ")])],1):t._e()],1)})),s("th")],2)]),s("tbody",t._l(t.bandedHistory,(function(e){return s("tr",{key:e.year},[s("td",{staticClass:"text-right"},[t._v(t._s(e.year))]),t._l(t.bands,(function(a){return s("td",{key:a.bands,staticClass:"text-center"},[t._v("\n          "+t._s(t.getBandData(a,e))+"\n        ")])})),s("td",["Avg Last 3 Yrs"===e.year?s("q-icon",{attrs:{size:"xs",color:"font",name:"fad fa-question-circle"}},[s("q-tooltip",{attrs:{"content-class":"bg-tertiary"}},[t._v("\n              Last 3 (if available) year's grades are treated as a single cohort with the final absolute values divided by 3.\n            ")])],1):t._e()],1)],2)})),0)]):t._e()],1)},bt=[],mt={name:"HOD.Metrics.MLO.ProfileTable",props:{history:{type:Array,required:!0},bandedHistory:{type:Array,required:!0},grades:{type:Array,required:!0},bands:{type:Array,required:!0},title:{tyle:String,default:""}},data(){return{mode:"percentage",modes:[{value:"percentage",label:"Percentage"},{value:"absolute",label:"Absolute"}],style:"banded",styles:[{value:"banded",label:"Banded"},{value:"linear",label:"Linear"}]}},computed:{ordered(){return parseInt(this.profile[0])<7?this.profile.split().reverse():this.profile}},methods:{getGrade(t,e){var s=e.results.find((function(e){return e.grade===t}));return s?"absolute"===this.mode||"GCSE Avg"===t?s.count:s.pct:"-"},getBandData(t,e){var s=e.results.find((function(e){return e.band===t}));if(!s)return"-";var a="absolute"===this.mode?s.abs:s.pct;return 0===a?"":a}},watch:{},components:{},created(){}},yt=mt,ft=s("6dd6"),gt=s("3aaf"),vt=Object(k["a"])(yt,pt,bt,!1,null,"5191f8cd",null),xt=vt.exports;W()(vt,"components",{QBtnToggle:ft["a"],QMarkupTable:S["a"],QIcon:Z["a"],QTooltip:gt["a"]});var wt=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("div",{ref:"stacked",staticStyle:{height:"200px"}})])},Ct=[],kt=s("915b"),qt=s("3099"),_t=s("3903");kt["f"](_t["a"]);var St={name:"HOD.Metrics.MLO.HistoryStack",props:{profile:{type:Array,required:!0},grades:{type:Array,required:!0},title:{tyle:String,default:""}},data(){return{stacked:null,chart:null}},methods:{createStacked(){var t=kt["c"](this.$refs.stacked,qt["l"]);this.stacked=t,t.hiddenState.properties.opacity=0,t.colors.step=4,t.padding(10,10,10,10);var e=t.xAxes.push(new qt["a"]);e.dataFields.category="year",e.renderer.grid.template.location=0,e.renderer.minGridDistance=50;var s=t.yAxes.push(new qt["k"]);s.min=0,s.max=100,s.strictMinMax=!0,s.calculateTotals=!0,s.renderer.minWidth=50,t.data=this.stackData()},addStackSeries(t,e){var s=t.series.push(new qt["c"]);s.sequencedInterpolation=!0,s.columns.template.width=kt["e"](80),s.columns.template.tooltipText="[bold]{name}[/][font-size:14px]: {valueY.totalPercent.formatNumber('#.00')}%",s.name=e,s.dataFields.categoryX="year",s.dataFields.valueY=e,s.dataFields.valueYShow="totalPercent",s.dataItems.template.locations.categoryX=.5,s.stacked=!0,s.tooltip.pointerOrientation="vertical";var a=this.getColor(e);s.fill=kt["b"](a)},getColor(t){var e="";switch(t){case"9":case"D1":e="#FF00FF";break;case"D2":case"A*":case"8":e="#9900ff";break;case"D3":case"A":case"7":e="#0000ff";break;case"M1":case"B":case"6":e="#4a86e8";break;case"M2":case"C":case"5":e="#00ffff";break;case"M3":case"D":case"4":e="#00ff00";break;case"P1":case"E":case"3":e="#ffff00";break;case"P2":case"2":e="#ff9900";break;case"U":e="#ff0000";break;default:e="#980000"}return e},stackData(){var t=this;return this.grades.forEach((function(e){Number.isInteger(e.grade)||t.stacked.colors.reset(),"GCSE Avg"!==e.grade&&t.addStackSeries(t.stacked,e.grade)})),this.profile}},computed:{},watch:{},components:{},created(){},mounted(){this.createStacked()},beforeDestroy(){this.stacked.dispose()}},Ot=St,Wt=Object(k["a"])(Ot,wt,Ct,!1,null,"5e19ad04",null),jt=Wt.exports,$t=s("8206"),Qt=s.n($t),Dt={name:"HOD.Data.MLO",props:{},data(){return{year:null,exam:null,subject:null,students:[],maxMLOCount:4,splitter:60,loading:!0,request:null,componentKey:1}},methods:{process(t){t.year===this.year&&t.id===this.subject.id&&t.examId===this.exam&&(this.componentKey++,this.students=t.students,this.maxMLOCount=t.maxMLOCount,this.subject=t,this.loading=!1)},getHistory(){this.loading=!0,this.request&&this.request.cancel(),this.request=Qt.a.CancelToken.source(),this.year=this.$store.getters["hod/activeYear"],this.exam=this.$store.getters["hod/activeExam"],this.subject=this.$store.getters["user/getGlobalSubject"],ht.getExamHistory(this.process,this.$errorFunction,this.year,this.exam,this.request)}},computed:{},watch:{},components:{HistoryTable:xt,HistoryStack:jt,Loading:g["a"]},created(){var t=this;this.subject=this.$store.getters["user/getGlobalSubject"],this.getHistory(),this.subWatch=this.$store.watch((function(){return t.$store.getters["user/getGlobalSubject"]}),this.getHistory),this.yearWatch=this.$store.watch((function(){return t.$store.getters["hod/activeYear"]}),this.getHistory),this.examWatch=this.$store.watch((function(){return t.$store.getters["hod/activeExam"]}),this.getHistory)},beforeDestroy(){this.subWatch(),this.yearWatch(),this.examWatch()}},Et=Dt,Tt=(s("f1e9"),Object(k["a"])(Et,dt,ut,!1,null,"7c11e2db",null)),Mt=Tt.exports;function Nt(t,e){var s=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),s.push.apply(s,a)}return s}function At(t){for(var e=1;e<arguments.length;e++){var s=null!=arguments[e]?arguments[e]:{};e%2?Nt(Object(s),!0).forEach((function(e){c()(t,e,s[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(s)):Nt(Object(s)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(s,e))}))}return t}var Pt={name:"Page.HOD.Metrics",data(){return{elements_:[{name:"wyap",label:"wyap",component:lt,tooltip:"Whole Year Assessment Point"}]}},computed:At(At(At({},Object(l["c"])("user",["getGlobalSubject"])),Object(l["c"])("hod",["activeYear"])),{},{elements(){var t=[{name:"history",label:"history",component:Mt}];return t=[],11===this.activeYear||13===this.activeYear?[].concat(n()(this.elements_),n()(t)):this.elements_}}),methods:At({},Object(l["d"])("hod",["setActiveYear"])),components:{toolbarPage:h["a"],YearBar:d["a"],ExamBar:u["a"]},created(){var t=this;this.setActiveYear(13),this.subjectWatch=this.$store.watch((function(){return t.$store.getters["user/getGlobalSubject"]}),this.setClass),this.yearWatch=this.$store.watch((function(){return t.$store.getters["hod/activeYear"]}),this.setClass)},beforeDestroy(){this.subjectWatch&&this.subjectWatch(),this.yearWatch&&this.yearWatch()}},Gt=Pt,Ht=Object(k["a"])(Gt,a,r,!1,null,null,null);e["default"]=Ht.exports},f1e9:function(t,e,s){"use strict";var a=s("e4b7"),r=s.n(a);r.a}}]);