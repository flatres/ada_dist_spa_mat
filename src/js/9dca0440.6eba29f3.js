(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["9dca0440"],{"009d":function(e,t,s){var a=s("ef37"),n=s("a97a")(!1);a(a.S,"Object",{values:function(e){return n(e)}})},"08e9":function(e,t,s){"use strict";var a=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("q-page",{staticClass:"no-scroll toolbar-page"},[s("q-toolbar",{class:{"text-white bg-toolbar":e.isDark,"text-black bg-white-3":e.isLight},attrs:{dense:"",shrink:"",classx:"text-white shadow-2 rounded-borders narrowx justify"}},[s("q-tabs",{staticClass:"tbp-tabs",attrs:{dense:"",shrink:"","active-color":e.isLight?"#011b48":"primary"},model:{value:e.selectedTab,callback:function(t){e.selectedTab=t},expression:"selectedTab"}},e._l(e.elements,function(t){return s("div",{key:t.name},[t.menu?e._e():s("q-tab",{attrs:{label:t.label,name:t.name,icon:t.icon}}),t.menu?s("q-btn",{attrs:{flat:"",size:"sm",label:t.label,icon:t.icon?t.icon:"fal fa-caret-down","text-color":e.isDark?"white":"primary"}},[s("q-menu",{ref:"settingsPopover",refInFor:!0,attrs:{"content-class":"bg-grey-9 text-white","auto-close":""}},[s("q-list",{attrs:{"item-separator":"",link:"","content-class":"bg-primary"}},e._l(t.menu,function(t){return s("q-item",{key:t.name,attrs:{clickable:""},nativeOn:{click:function(s){return e.clickMenu(t)}}},[s("q-item-section",{attrs:{avatar:"",left:"",dark:""}},[s("q-icon",{attrs:{size:"20px",name:t.icon}})],1),s("q-item-section",[s("q-item-label",[e._v(e._s(t.label))])],1)],1)}),1)],1)],1):e._e()],1)}),0),s("q-space"),e._t("side")],2),s("q-tab-panels",{model:{value:e.selectedTab,callback:function(t){e.selectedTab=t},expression:"selectedTab"}},e._l(e.tabPanels,function(t){return s("q-tab-panel",{key:t.name,attrs:{name:t.name}},[s(t.component,{tag:"component",on:{close:e.close}})],1)}),1)],1)},n=[],r=(s("e125"),s("4823"),s("2e73"),s("dde3"),s("76d0"),s("0c1f"),s("c880"),s("8e9e")),o=s.n(r),i=s("9ce4");function l(e,t){var s=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter(function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable})),s.push.apply(s,a)}return s}function c(e){for(var t=1;t<arguments.length;t++){var s=null!=arguments[t]?arguments[t]:{};t%2?l(s,!0).forEach(function(t){o()(e,t,s[t])}):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(s)):l(s).forEach(function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(s,t))})}return e}var u={name:"ComponentPageToolbar",props:{default:null,elements:null},watch:{selectedTab:function(){console.log(":",this.selectedTab)}},data:function(){return{selectedTab:null}},computed:c({},Object(i["e"])("user",["isDark","isLight"]),{tabPanels:function(){var e=[];return this.elements.forEach(function(t){t.menu?t.menu.forEach(function(t){e.push({name:t.name,component:t.component})}):e.push({name:t.name,component:t.component})}),e}}),methods:{close:function(){this.selectedTab=this.default},clickMenu:function(e){e.name&&(this.selectedTab=e.name),e.event&&this.$emit(e.event)}},created:function(){this.selectedTab=this.default}},d=u,f=(s("b0d4"),s("2be6")),b=Object(f["a"])(d,a,n,!1,null,null,null);t["a"]=b.exports},2457:function(e,t,s){"use strict";s.r(t);var a=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",[e.isWorking||e.isError||e.showConsole?s("div",[s("div",{staticClass:"row"},[s("div",{staticClass:"col"},[s("q-btn",{staticClass:"q-mt-md float-right no-shadow",attrs:{"text-color":"white",size:"sm",icon:"fal fa-terminal"},on:{click:function(t){e.showConsole=!e.showConsole}}})],1)]),s("console-component",{staticStyle:{"min-height":"400px"}})],1):e._e(),e.isWorking||e.isError||e.showConsole?e._e():s("toolbar-page",{attrs:{elements:e.elements,default:"results"},on:{spreadsheet:e.clickSpreadsheet},scopedSlots:e._u([{key:"side",fn:function(){return[s("h1",{staticStyle:{"font-size":"12px"}},[e._v(e._s(e.timestamp))]),s("q-btn",{staticClass:"no-shadow",attrs:{size:"sm",icon:"fal fa-retweet-alt"},on:{click:e.refreshSession}}),s("q-btn",{staticClass:"no-shadow",attrs:{size:"sm",icon:"fal fa-terminal"},on:{click:function(t){e.showConsole=!e.showConsole}}})]},proxy:!0}],null,!1,3292533018)})],1)},n=[],r=(s("e125"),s("4823"),s("2e73"),s("dde3"),s("76d0"),s("0c1f"),s("8e9e")),o=s.n(r),i=s("9ce4"),l=s("08e9"),c=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("crud",{ref:"crud",attrs:{dataOverride:e.data,columns:e.columns,search:"",filterchips:""}})},u=[],d=(s("288e"),s("d612"));function f(e,t){var s=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter(function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable})),s.push.apply(s,a)}return s}function b(e){for(var t=1;t<arguments.length;t++){var s=null!=arguments[t]?arguments[t]:{};t%2?f(s,!0).forEach(function(t){o()(e,t,s[t])}):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(s)):f(s).forEach(function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(s,t))})}return e}var m={name:"ResultsDataTable",data:function(){return{columns:[{name:"id",label:"id",field:"id"},{name:"schoolId",label:"School Id",field:"txtSchoolID"},{name:"surname",required:!0,label:"Surname",align:"left",field:"txtSurname",sortable:!0},{name:"forename",label:"Forename",field:"txtForename",sortable:!0},{name:"gender",label:"M/F",field:"txtGender",sortable:!0},{name:"boarding",label:"House",field:"txtHouseCode",sortable:!0,type:"string",filter:!0,options:[]},{name:"exam",label:"Quali",field:"txtQualification"},{name:"Subject Code",label:"Code",field:"subjectCode",type:"string",sortable:!0,editable:!0,filter:!0,options:[]},{name:"grade",label:"Grade",field:"grade",sortable:!0,type:"string",filter:!0,options:[]},{name:"examTitle",label:"Exam",field:"txtOptionTitle"}],data:[]}},methods:{setData:function(e){this.data=this.resultsGCSE,this.columns.find(function(e){return"subjectCode"===e.field}).options=this.$parseOptions(this.data,"subjectCode"),this.columns.find(function(e){return"txtHouseCode"===e.field}).options=this.$parseOptions(this.data,"txtHouseCode"),this.columns.find(function(e){return"grade"===e.field}).options=this.$parseOptions(this.data,"grade"),console.log(this.columns)}},computed:b({},Object(i["c"])("exams",["resultsGCSE"]),{tableClass:function(){if(this.dark)return"bg-black"}}),components:{Crud:d["a"]},created:function(){var e=this;this.setData(),this.$store.watch(function(){return e.$store.getters["exams/resultsGCSE"]},this.setData)}},p=m,h=(s("fad3"),s("2be6")),g=Object(h["a"])(p,c,u,!1,null,"22431ef7",null),O=g.exports,y=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",[s("div",{staticClass:"q-pa-md text-white"},[s("q-btn-toggle",{attrs:{dark:"","toggle-color":"secondary",options:[{label:"Hundred",value:11},{label:"Remove",value:10},{label:"Shell",value:9}]},model:{value:e.year,callback:function(t){e.year=t},expression:"year"}})],1),s("crud",{ref:"crud",attrs:{dataOverride:e.currentData,columns:e.columns,search:"",filterchips:"",fullscreen:""}})],1)},v=[];s("009d");function S(e,t){var s=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter(function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable})),s.push.apply(s,a)}return s}function C(e){for(var t=1;t<arguments.length;t++){var s=null!=arguments[t]?arguments[t]:{};t%2?S(s,!0).forEach(function(t){o()(e,t,s[t])}):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(s)):S(s).forEach(function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(s,t))})}return e}var j={name:"StudentResultsDataTable",data:function(){return{year:null,data:[]}},computed:C({},Object(i["c"])("exams",["resultsGCSE","statisticsGCSE"]),{columns:function(){for(var e=[{name:"txtInitialedName",required:!0,label:"Name",align:"left",field:"txtInitialedName",sortable:!0,sticky:!0},{name:"gender",label:"M/F",field:"txtGender",sortable:!0},{name:"house",label:"Hs",field:"txtHouseCode",sortable:!0}],t=this.currentStats.subjectResults,s=0,a=Object.keys(t);s<a.length;s++){var n=a[s];e.push({name:n,label:n,field:n,sortable:!0})}return e.push({name:"total",label:"Total",field:"resultCount",sortable:!0}),e.push({name:"gradeAverage",label:"Grade Avg.",field:"gradeAverage",sortable:!0}),e},currentStats:function(){switch(this.year){case 11:return this.statisticsGCSE.hundredStats;case 10:return this.statisticsGCSE.removeStats;case 9:return this.statisticsGCSE.shellStats}},currentData:function(){return Object.values(this.currentStats.allStudents)}}),methods:{setData:function(){this.year=this.statisticsGCSE.years[0].value,this.data=Object.values(this.currentStats.allStudents)}},components:{Crud:d["a"]},created:function(){var e=this;this.setData(),this.$store.watch(function(){return e.$store.getters["exams/resultsGCSE"]},this.setData)}},w=j,E=(s("9034"),Object(h["a"])(w,y,v,!1,null,"e23cf874",null)),D=E.exports,P=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",[s("crud",{ref:"crud",attrs:{dataOverride:e.mappedData,columns:e.columns,search:"",filterchips:"",fullscreen:""}})],1)},x=[];function k(e,t){var s=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter(function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable})),s.push.apply(s,a)}return s}function G(e){for(var t=1;t<arguments.length;t++){var s=null!=arguments[t]?arguments[t]:{};t%2?k(s,!0).forEach(function(t){o()(e,t,s[t])}):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(s)):k(s).forEach(function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(s,t))})}return e}var _={name:"SubjectResultsDataTable",data:function(){return{year:11,data:[]}},computed:G({},Object(i["c"])("exams",["resultsGCSE","statisticsGCSE"]),{columns:function(){var e=[{name:"subjectName",required:!0,label:"Subject",align:"left",field:"subjectName",sortable:!0,sticky:!0},{name:"boardName",required:!0,label:"Board",align:"left",field:"boardName",sortable:!0},{name:"position",label:"Position",field:"position",sortable:!0},{name:"candidates",label:"Candidates",field:"candidates",sortable:!0},{name:"gradeAverage",label:"Grade Avg.",field:"gradeAvg",sortable:!0},{name:"ABs",label:"% AB",field:"ABpct",sortable:!0},{name:"passes",label:"Passes",field:"passes",sortable:!0,align:"right"},{name:"%pass",label:"% Pass ",field:"passRate",sortable:!0,align:"left"},{name:"fails",label:"Fails",field:"fails",sortable:!0},{name:"astar",label:"A*",field:"Astar",sortable:!0},{name:"a",label:"A",field:"A",sortable:!0},{name:"b",label:"B",field:"B",sortable:!0},{name:"c",label:"C",field:"C",sortable:!0},{name:"d",label:"D",field:"D",sortable:!0},{name:"e",label:"E",field:"E",sortable:!0},{name:"u",label:"U",field:"U",sortable:!0}];return e},yearList:function(){return this.statistics.years},mappedData:function(){return this.data.map(function(e){return{subjectName:e.subjectName,boardName:e.boardName,position:e.position,candidates:e.summaryData["candidateCount"],gradeAvg:e.summaryData["gradeAverage"],ABpct:e.summaryData["%ABs"],passes:e.passes,fails:e.fails,passRate:e.summaryData["%passRate"],Astar:e.gradeCounts["A*"],A:e.gradeCounts["A"],B:e.gradeCounts["B"],C:e.gradeCounts["C"],D:e.gradeCounts["D"],E:e.gradeCounts["E"],U:e.gradeCounts["U"]}})},currentStats:function(){switch(this.year){case 11:return this.statisticsGCSE.hundredStats;case 10:return this.statisticsGCSE.removeStats;case 9:return this.statisticsGCSE.shellStats}}}),methods:{setData:function(){this.year=this.statisticsGCSE.years[0].value,this.data=Object.values(this.currentStats.subjectResults)}},components:{Crud:d["a"]},created:function(){var e=this;this.setData(),this.$store.watch(function(){return e.$store.getters["exams/resultsGCSE"]},this.setData)}},A=_,q=(s("b8a9"),Object(h["a"])(A,P,x,!1,null,"37adb69c",null)),$=q.exports,T=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",[s("crud",{ref:"crud",attrs:{dataOverride:e.mappedData,columns:e.columns,search:"",filterchips:"",fullscreen:""}})],1)},B=[];function R(e,t){var s=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter(function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable})),s.push.apply(s,a)}return s}function H(e){for(var t=1;t<arguments.length;t++){var s=null!=arguments[t]?arguments[t]:{};t%2?R(s,!0).forEach(function(t){o()(e,t,s[t])}):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(s)):R(s).forEach(function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(s,t))})}return e}var I={name:"SubjectResultsDataTable",data:function(){return{year:11,data:[]}},computed:H({},Object(i["c"])("exams",["resultsGCSE","statisticsGCSE"]),{columns:function(){var e=[{name:"houseName",required:!0,label:"House",align:"left",field:"txtHouseCode",sortable:!0,sticky:!0},{name:"position",label:"Position",field:"position",sortable:!0},{name:"gradeAverage",label:"Grade Avg.",field:"gradeAverage",sortable:!0},{name:"sixOrMore",label:"> 6 As",field:"sixOrMore",sortable:!0},{name:"astar",label:"A*",field:"Astar",sortable:!0},{name:"a",label:"A",field:"A",sortable:!0},{name:"b",label:"B",field:"B",sortable:!0},{name:"c",label:"C",field:"C",sortable:!0},{name:"d",label:"D",field:"D",sortable:!0},{name:"e",label:"E",field:"E",sortable:!0},{name:"u",label:"U",field:"U",sortable:!0},{name:"passes",label:"Passes",field:"passes",sortable:!0},{name:"fails",label:"Fails",field:"fails",sortable:!0}];return e},yearList:function(){return this.statistics.years},mappedData:function(){return this.data.map(function(e){return{txtHouseCode:e.txtHouseCode,position:e.position,gradeAverage:e.summaryData["gradeAverage"],sixOrMore:e.summaryData["sixOrMoreAs"],passes:e.passes,fails:e.fails,Astar:e.gradeCounts["A*"],A:e.gradeCounts["A"],B:e.gradeCounts["B"],C:e.gradeCounts["C"],D:e.gradeCounts["D"],E:e.gradeCounts["E"],U:e.gradeCounts["U"]}})},currentStats:function(){switch(this.year){case 11:return this.statisticsGCSE.hundredStats;case 10:return this.statisticsGCSE.removeStats;case 9:return this.statisticsGCSE.shellStats}}}),methods:{setData:function(){this.year=this.statisticsGCSE.years[0].value,this.data=Object.values(this.currentStats.houseResults)}},components:{Crud:d["a"]},created:function(){var e=this;this.setData(),this.$store.watch(function(){return e.$store.getters["exams/resultsGCSE"]},this.setData)}},N=I,L=(s("e668"),Object(h["a"])(N,T,B,!1,null,"1d779f03",null)),M=L.exports,F=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",[s("p",{staticClass:"text-secondary"},[e._v("Summary for Hundred Candidates")]),s("crud",{ref:"crud",attrs:{dataOverride:e.summaryData,columns:e.columns,fullscreenx:""}})],1)},U=[];function W(e,t){var s=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter(function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable})),s.push.apply(s,a)}return s}function z(e){for(var t=1;t<arguments.length;t++){var s=null!=arguments[t]?arguments[t]:{};t%2?W(s,!0).forEach(function(t){o()(e,t,s[t])}):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(s)):W(s).forEach(function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(s,t))})}return e}var J={name:"ExamsGCSESummaryData",data:function(){return{columns:[{name:"desc",label:"Description",field:"desc",align:"left"},{name:"Boys",label:"Boys",field:"M_val",align:"right"},{name:"Boys%",label:"%",field:"M_val%",align:"left",format:function(e){return"undefined"!==typeof e?"".concat(e,"%"):""}},{name:"Girls",label:"Girls",field:"F_val",align:"right"},{name:"Girls%",label:"%",field:"F_val%",align:"left",format:function(e){return"undefined"!==typeof e?"".concat(e,"%"):""}},{name:"Total",label:"Total",field:"total_val",align:"right"},{name:"Total%",label:"%",field:"total_val%",align:"left",format:function(e){return"undefined"!==typeof e?"".concat(e,"%"):""}}]}},computed:z({},Object(i["c"])("exams",["resultsGCSE","statisticsGCSE"]),{summaryData:function(){return this.statisticsGCSE.hundredStats.summaryData}}),components:{Crud:d["a"]}},Q=J,K=(s("c64a"),Object(h["a"])(Q,F,U,!1,null,"7b7de85f",null)),V=K.exports,X=s("dd14"),Y=s("de53");function Z(e,t){var s=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter(function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable})),s.push.apply(s,a)}return s}function ee(e){for(var t=1;t<arguments.length;t++){var s=null!=arguments[t]?arguments[t]:{};t%2?Z(s,!0).forEach(function(t){o()(e,t,s[t])}):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(s)):Z(s).forEach(function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(s,t))})}return e}var te={name:"PageExamsGCSE",data:function(){return{timestamp:"",elements:[{name:"results",label:"Results",component:O,shortcut:"r"},{name:"students",label:"Students",component:D,shortcut:"s"},{name:"subjects",label:"Subjects",component:$,shortcut:"u"},{name:"houses",label:"Houses",component:M,shortcut:"h"},{name:"statistics",label:"Statistics",component:V,shortcut:"t"},{name:"downloads",label:"",icon:"fal fa-xs fa-download",menu:[{event:"spreadsheet",label:"Spreadsheet",icon:"fal fa-file-excel"}]}],currentSessionId:"",isWorking:!1,isLoaded:!1,isError:!1,showConsole:!1}},methods:ee({},Object(i["b"])("sockets",["clearConsoleLog"]),{},Object(i["d"])("exams",["setResultsGCSE","setStatisticsGCSE"]),{refreshSession:function(){!0!==this.isWorking&&(this.showConsole=!0,this.clearConsoleLog(),this.isWorking=!0,this.isError=!1,this.$q.loading.show(),Y["a"].getSession(this.loadSession,null,{level:"gcse",sessionID:this.activeSession}))},setSession:function(e){!0!==this.isWorking&&(this.clearConsoleLog(),this.isWorking=!0,this.isError=!1,this.currentSessionId=e,this.$q.loading.show(),Y["a"].getSessionCache(this.loadSession,null,{level:"gcse",sessionID:e}))},loadSession:function(e){this.showConsole=!1,this.isWorking=!1,this.setResultsGCSE(e.results),this.setStatisticsGCSE(e.statistics),this.timestamp=e.timestamp,this.$q.loading.hide()},clickSpreadsheet:function(){this.$downloadBlob(this.statisticsGCSE.spreadsheet.path,this.statisticsGCSE.cemSpreadsheet.filename)},clickCemSpreadsheet:function(){this.$downloadBlob(this.statisticsGCSE.spreadsheet.path,this.statisticsGCSE.cemSpreadsheet.filename)}}),computed:ee({},Object(i["e"])("exams",["activeSession","resultsGCSE","statisticsGCSE"])),components:{toolbarPage:l["a"],ConsoleComponent:X["a"]},watch:{activeSession:function(e,t){this.setSession(e)}},created:function(){console.log(this.activeSession),this.activeSession&&this.setSession(this.activeSession)}},se=te,ae=Object(h["a"])(se,a,n,!1,null,null,null);t["default"]=ae.exports},2541:function(e,t,s){},"2ed3":function(e,t,s){},"311d":function(e,t,s){},"50dd":function(e,t,s){},"8dd7":function(e,t,s){},9034:function(e,t,s){"use strict";var a=s("d20c"),n=s.n(a);n.a},b0d4:function(e,t,s){"use strict";var a=s("b3f7"),n=s.n(a);n.a},b3f7:function(e,t,s){},b8a9:function(e,t,s){"use strict";var a=s("311d"),n=s.n(a);n.a},c64a:function(e,t,s){"use strict";var a=s("50dd"),n=s.n(a);n.a},d20c:function(e,t,s){},dd14:function(e,t,s){"use strict";var a=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"console q-ma-md full-height overflow-y:scroll"},[s("p",{staticClass:"q-ma-sm"},[e._v("Console")]),s("ul",e._l(e.consoleLog,function(t){return s("li",{key:t.lineIndex,class:{error:t.isError,indent1:1==t.indent,indent2:2==t.indent,indent3:3==t.indent}},[e._v("\n      "+e._s(t.message)+"\n    ")])}),0)])},n=[],r=(s("e125"),s("4823"),s("2e73"),s("dde3"),s("76d0"),s("0c1f"),s("8e9e")),o=s.n(r),i=s("9ce4");function l(e,t){var s=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter(function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable})),s.push.apply(s,a)}return s}function c(e){for(var t=1;t<arguments.length;t++){var s=null!=arguments[t]?arguments[t]:{};t%2?l(s,!0).forEach(function(t){o()(e,t,s[t])}):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(s)):l(s).forEach(function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(s,t))})}return e}var u={name:"AppConsole",data:function(){return{}},computed:c({},Object(i["c"])("sockets",["consoleLog"]))},d=u,f=(s("e510"),s("2be6")),b=Object(f["a"])(d,a,n,!1,null,"624905aa",null);t["a"]=b.exports},de53:function(e,t,s){"use strict";var a=s("4778");t["a"]={getSessions:function(e,t){a["a"].get("/exams/sessions").then(function(t){e(t.data)}).catch(function(e){e()})},getSession:function(e,t,s){console.log(s),a["a"].get("/exams/"+s.level+"/results/"+s.sessionID).then(function(t){console.log("here",t),e(t.data)}).catch(function(e){console.log("error",e),t(e)})},getSessionCache:function(e,t,s){console.log(s),a["a"].get("/exams/cache/"+s.level+"/results/"+s.sessionID).then(function(t){console.log("here",t),e(t.data)}).catch(function(e){console.log("error",e),t(e)})}}},e510:function(e,t,s){"use strict";var a=s("2541"),n=s.n(a);n.a},e668:function(e,t,s){"use strict";var a=s("8dd7"),n=s.n(a);n.a},fad3:function(e,t,s){"use strict";var a=s("2ed3"),n=s.n(a);n.a}}]);