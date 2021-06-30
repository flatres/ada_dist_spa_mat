(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[5],{"0d48":function(e,n){},1:function(e,n,t){e.exports=t("71d8")},"452d":function(e,n,t){"use strict";var r={};t.r(r),t.d(r,"userId",(function(){return L})),t.d(r,"name",(function(){return j})),t.d(r,"permissions",(function(){return Y})),t.d(r,"isDarkMode",(function(){return O})),t.d(r,"isAuthenticated",(function(){return G})),t.d(r,"getModuleAccess",(function(){return $})),t.d(r,"getModuleColor",(function(){return B})),t.d(r,"getPageAccess",(function(){return F})),t.d(r,"menuIsOpen",(function(){return R})),t.d(r,"currentModule",(function(){return H})),t.d(r,"getGlobalSubject",(function(){return U})),t.d(r,"getGlobalStudent",(function(){return N})),t.d(r,"getGlobalHouse",(function(){return V}));var o={};t.r(o),t.d(o,"authUser",(function(){return K})),t.d(o,"permissions",(function(){return X})),t.d(o,"roles",(function(){return Z})),t.d(o,"clearAuthData",(function(){return ee})),t.d(o,"isDarkMode",(function(){return ne})),t.d(o,"role",(function(){return te})),t.d(o,"toggleMenu",(function(){return re})),t.d(o,"setModule",(function(){return oe})),t.d(o,"setGlobalStudent",(function(){return ae})),t.d(o,"setGlobalSubject",(function(){return ie})),t.d(o,"setGlobalHouse",(function(){return ue}));var a={};t.r(a),t.d(a,"login",(function(){return se})),t.d(a,"fetchPermissions",(function(){return le})),t.d(a,"tryAutoLogin",(function(){return de})),t.d(a,"logout",(function(){return fe})),t.d(a,"isDarkMode",(function(){return pe})),t.d(a,"setAxiosHeader",(function(){return me})),t.d(a,"setPrimaryColor",(function(){return he})),t.d(a,"toggleMenu",(function(){return be})),t.d(a,"setModule",(function(){return ge})),t.d(a,"setGlobalStudent",(function(){return ve})),t.d(a,"setGlobalHouse",(function(){return Pe})),t.d(a,"setGlobalSubject",(function(){return Se}));var i={};t.r(i),t.d(i,"isConnected",(function(){return ke})),t.d(i,"consoleLog",(function(){return ye})),t.d(i,"updaterData",(function(){return xe})),t.d(i,"updater",(function(){return Ae})),t.d(i,"progress",(function(){return Ee})),t.d(i,"progressMessage",(function(){return De}));var u={};t.r(u),t.d(u,"consoleLog",(function(){return Me})),t.d(u,"updater",(function(){return Le})),t.d(u,"notify",(function(){return je})),t.d(u,"progress",(function(){return Ye})),t.d(u,"clearConsoleLog",(function(){return Oe})),t.d(u,"isConnected",(function(){return Ge}));var c={};t.r(c),t.d(c,"connectSocket",(function(){return Be})),t.d(c,"clearConsoleLog",(function(){return Fe}));var s={};t.r(s),t.d(s,"activeClass",(function(){return Ue}));var l={};t.r(l),t.d(l,"setActiveClass",(function(){return Ne}));var d={};t.r(d),t.d(d,"activeSession",(function(){return We})),t.d(d,"coachOutFilter",(function(){return Je})),t.d(d,"coachRetFilter",(function(){return Te}));var f={};t.r(f),t.d(f,"setActiveSession",(function(){return ze})),t.d(f,"setCoachOutFilter",(function(){return Qe})),t.d(f,"setCoachRetFilter",(function(){return Ke}));var p={};t.r(p),t.d(p,"activeStudent",(function(){return nn}));var m={};t.r(m),t.d(m,"setActiveStudent",(function(){return tn}));var h={};t.r(h),t.d(h,"activeSession",(function(){return un})),t.d(h,"resultsGCSE",(function(){return cn})),t.d(h,"statisticsGCSE",(function(){return sn})),t.d(h,"resultsALevel",(function(){return ln})),t.d(h,"statisticsALevel",(function(){return dn}));var b={};t.r(b),t.d(b,"setActiveSession",(function(){return fn})),t.d(b,"setStatisticsGCSE",(function(){return pn})),t.d(b,"setResultsGCSE",(function(){return mn})),t.d(b,"setStatisticsALevel",(function(){return hn})),t.d(b,"setResultsALevel",(function(){return bn}));var g={};t.r(g),t.d(g,"activeSubject",(function(){return Sn})),t.d(g,"activeYear",(function(){return wn})),t.d(g,"activeYearCode",(function(){return Cn})),t.d(g,"activeExam",(function(){return kn}));var v={};t.r(v),t.d(v,"setActiveSubject",(function(){return yn})),t.d(v,"setActiveYear",(function(){return xn})),t.d(v,"setActiveYearCode",(function(){return An})),t.d(v,"setActiveExam",(function(){return En}));var P={};t.r(P),t.d(P,"activeSubject",(function(){return Ln})),t.d(P,"activeYear",(function(){return jn})),t.d(P,"activeYearCode",(function(){return Yn})),t.d(P,"activeExam",(function(){return On}));var S={};t.r(S),t.d(S,"setActiveSubject",(function(){return Gn})),t.d(S,"setActiveYear",(function(){return $n})),t.d(S,"setActiveYearCode",(function(){return Bn})),t.d(S,"setActiveExam",(function(){return Fn}));var w=t("9869"),C=t("9ce4"),k=t("fa94"),y=t.n(k),x={},A=t("fe3b"),E=t("bf1d"),D=t("f1a8"),I={namespaced:!0,state:x,getters:A,mutations:E,actions:D},M={userId:null,auth:null,firstname:null,lastname:null,permissions:{},roles:[],isDarkMode:!0,menuIsOpen:!0,moduleIcon:"",moduleName:"",globalSubject:null,globalStudent:null,globalHouse:null},L=function(e){return e.userId},j=function(e){var n={firstname:e.firstname,lastname:e.lastname};return n},Y=function(e){return e.permissions},O=function(e){return e.isDarkMode},G=function(e){return null!==e.auth},$=function(e){return function(n){return!!e.permissions[n]&&e.permissions[n].hasAccess}},B=function(e){return function(n){return e.permissions[n]?e.permissions[n].color:"#4fc08d"}},F=function(e){return function(n,t){if(!e.permissions[n])return!1;var r=e.permissions[n];return!!r.pages[t]&&r.pages[t].hasAccess}},R=function(e){return e.menuIsOpen},H=function(e){return{icon:e.moduleIcon,name:e.moduleName}},U=function(e){return e.globalSubject},N=function(e){return e.globalStudent},V=function(e){return e.globalHouse},_=(t("c880"),t("3cc3")),q=t.n(_),W=(t("2e73"),t("76d0"),t("0e20"),t("bc9f")),J=t("156f"),T=t("134d"),z={neutral:"#E0E1E2",positive:"#a342ff",negative:"#DB2828",info:"#31CCEC",warning:"#f57c00",accentx:"#6A0DAD",accent:"#5a0e6b",accentSecondary:"#a693ab",primary:"#FFFFFF",secondary:"#f6f6f6",tertiary:"#e9e9e9",font:"#121212",fontSecondary:"#a8a8a8",fontNegative:"#E5E5E5"},Q={neutral:"#E0E1E2",positive:"#c6ff00",negative:"#DB2828",info:"#31CCEC",warning:"#e14d27",accent:"#40dce6",accentSecondary2:"#00353c",accentSecondary:"#303c46",primary:"#191f24",secondary:"#1d262b",tertiary:"#262f37",font:"#98a6b3",fontSecondary:"#9e9e9e",fontNegative:"#121212"},K=function(e,n){e.auth=n.auth,e.userId=n.userId,e.permissions=n.permissions,e.roles=n.roles,e.globalSubject=n.globalSubject,e.globalStudent=n.globalStudent,e.globalHouse=n.globalHouse},X=function(e,n){e.permissions=n},Z=function(e,n){e.roles=n},ee=function(e){e.auth=null,e.userId=null,e.permissions={},e.roles=[]},ne=function(e,n){e.isDarkMode=n,W["a"].set("isDarkMode",n);var t=n?Q:z;T["a"].setBrand("neutral",t.neutral),T["a"].setBrand("positive",t.positive),T["a"].setBrand("negative",t.negative),T["a"].setBrand("into",t.info),T["a"].setBrand("warning",t.warning),T["a"].setBrand("accent",t.accent),T["a"].setBrand("accent-secondary",t.accentSecondary),T["a"].setBrand("primary",t.primary),T["a"].setBrand("secondary",t.secondary),T["a"].setBrand("tertiary",t.tertiary),T["a"].setBrand("font",t.font),T["a"].setBrand("font-secondary",t.fontSecondary),T["a"].setBrand("font-negative",t.fontNegative),J["a"].set(n)},te=function(e,n){var t=n[0].data,r=n[0].roleId;Object.entries(t).forEach((function(n){var o=q()(n,2),a=o[0],i=o[1];if(e.permissions[a]){!0===t[a].hasAccess&&(e.permissions[a].hasAccess=!0,e.permissions[a].fromRoles.find((function(e){return e===r}))||e.permissions[a].fromRoles.push(r)),!1===t[a].hasAccess&&1===e.permissions[a].fromRoles.length&&(e.permissions[a].fromRoles=[],e.permissions[a].hasAccess=!1);var u=i.pages;Object.entries(u).forEach((function(n){var t=q()(n,2),o=t[0],i=t[1];e.permissions[a].pages[o]?e.permissions[a].pages[o].fromRoles.find((function(e){return e===r}))?1===e.permissions[a].pages[o].fromRoles.length&&w["a"].set(e.permissions[a].pages,o,i):!0===i.hasAccess&&(e.permissions[a].pages[o].hasAccess=!0,e.permissions[a].pages[o].fromRoles.push(r)):w["a"].set(e.permissions[a].pages,o,i)}))}else w["a"].set(e.permissions,a,i)})),W["a"].set("permissions",e.permissions)},re=function(e,n){e.menuIsOpen=n||!e.menuIsOpen},oe=function(e,n){e.moduleIcon=n.icon,e.moduleName=n.name},ae=function(e,n){e.globalStudent=n},ie=function(e,n){e.globalSubject=n},ue=function(e,n){e.globalHouse=n},ce=(t("c8a0"),t("47783")),se=function(e,n){var t=e.commit,r=e.dispatch;return new Promise((function(e,o){ce["a"].post("/auth/login",{login:n.login,password:n.password}).then((function(n){var o=n.data.loginObject;!0===n.data.success&&(t("authUser",{auth:o.auth,userId:o.userId,permissions:o.permissions,roles:o.roles,isDarkMode:o.isDarkMode}),W["a"].set("auth",o.auth),W["a"].set("userId",o.userId),W["a"].set("permissions",o.permissions),W["a"].set("roles",o.roles),W["a"].set("isDarkMode",o.isDarkMode),r("setAxiosHeader",o.auth),r("sockets/connectSocket",o.auth,{root:!0}),r("isDarkMode",o.isDarkMode)),e(n.data)})).catch((function(e){o(e)}))}))},le=function(e){var n=e.commit;e.dispatch;return new Promise((function(e,t){ce["a"].get("/auth/permissions").then((function(t){var r=t.data.permissions,o=t.data.roles;r&&(n("permissions",r),n("roles",o),W["a"].set("permissions",r),W["a"].set("roles",o)),e(t.data)})).catch((function(e){t(e)}))}))},de=function(e){var n=e.commit,t=e.dispatch,r=W["a"].getItem("auth");return null!==r&&(n("authUser",{auth:r,userId:W["a"].getItem("userId"),permissions:W["a"].getItem("permissions"),roles:W["a"].getItem("roles"),isDarkMode:W["a"].getItem("isDarkMode"),globalStudent:W["a"].getItem("globalStudent"),globalSubject:W["a"].getItem("globalSubject"),globalHouse:W["a"].getItem("globalHouse")}),t("isDarkMode",W["a"].getItem("isDarkMode")),t("setAxiosHeader",r),t("sockets/connectSocket",r,{root:!0}),t("fetchPermissions",r),!0)},fe=function(e){var n=e.commit,t=e.dispatch;W["a"].clear(),n("clearAuthData"),t("setAxiosHeader","")},pe=function(e,n){var t=e.commit;e.state;t("isDarkMode",n)},me=function(e,n){ce["a"].defaults.headers.common["Authorization"]=n},he=function(e,n){e.commit;return n===T["a"]},be=function(e){var n=e.commit;n("toggleMenu")},ge=function(e,n){var t=e.commit;t("setModule",n)},ve=function(e,n){var t=e.commit;W["a"].set("globalStudent",n),t("setGlobalStudent",n)},Pe=function(e,n){var t=e.commit;W["a"].set("globalHouse",n),t("setGlobalHouse",n)},Se=function(e,n){var t=e.commit;console.log(n),W["a"].set("globalSubject",n),t("setGlobalSubject",n)},we={namespaced:!0,state:M,getters:r,mutations:o,actions:a},Ce={isConnected:!1,consoleLog:[],updaters:{},updaterData:{},progress:{},progressMessage:{},progressIsComplete:{}},ke=function(e){return e.isConnected},ye=function(e){return e.consoleLog.slice().reverse()},xe=function(e){return function(n){return e.updaterData[n]}},Ae=function(e){return function(n,t){if(!e.updaters[n])return t;var r=e.updaters[n],o=r.key,a=r.data[o],i=t.findIndex((function(e){return e[o]===a}));return i>-1&&w["a"].set(t,i,r.data),t}},Ee=function(e){return function(n){return e.progress[n]?e.progress[n]:0}},De=function(e){return function(n){return e.progressMessage[n]?e.progressMessage[n]:""}},Ie=(t("ae66"),t("2405")),Me=function(e,n){if(!0===n.replace){for(var t=e.consoleLog.length,r=t-1;r>=0;r--)if(!1===e.consoleLog[r].isError){e.consoleLog[r].message=n.message;break}}else e.consoleLog.push(n)},Le=function(e,n){var t=n[0].updaterId;w["a"].set(e.updaters,t,n[0])},je=function(e,n){console.log("NOTIFY",n),Ie["a"].create({message:n[0].message,color:"primary",textColor:"black"})},Ye=function(e,n){var t=n[0].progressId;w["a"].set(e.progress,t,n[0].progress),n[0].message&&n[0].message.length>0&&w["a"].set(e.progressMessage,t,n[0].message),w["a"].set(e.progressIsComplete,t,n[0].isComplete)},Oe=function(e){e.consoleLog=[]},Ge=function(e,n){e.isConnected=n},$e=t("0c9c"),Be=function(e,n){var t=e.commit,r=e.rootState;w["a"].use($e["a"],{debug:!0,url:"wss://adazmq.marlboroughcollege.org/wss",realm:"realm1",onopen:function(e,n){t("isConnected",!0)},onclose:function(e,n){t("isConnected",!1)}}),n&&(w["a"].Wamp.subscribe("console_"+n,(function(e,n,r){t("consoleLog",e[0])}),{acknowledge:!0}).then((function(e){})),w["a"].Wamp.subscribe("updater_"+n,(function(e,n,r){t("updater",e)}),{acknowledge:!0}).then((function(e){})),w["a"].Wamp.subscribe("progress_"+n,(function(e,n,r){t("progress",e)}),{acknowledge:!0}).then((function(e){})),w["a"].Wamp.subscribe("notify_"+n,(function(e,n,r){t("notify",e)}),{acknowledge:!0}).then((function(e){})),r.user.roles.forEach((function(e,n){console.log(e),w["a"].Wamp.subscribe("role_"+e,(function(e,n,r){t("user/role",e,{root:!0})}),{acknowledge:!0}).then((function(e){}))})))},Fe=function(e){var n=e.commit;n("clearConsoleLog")},Re={namespaced:!0,state:Ce,getters:i,mutations:u,actions:c},He={activeClass:null},Ue=function(e){return e.activeClass},Ne=function(e,n){e.activeClass=n},Ve=t("0d48"),_e={namespaced:!0,state:He,getters:s,mutations:l,actions:Ve},qe={activeSession:null,activeSessionName:null,coachOutFilter:[],coachRetFilter:[]},We=function(e){return e.activeSession},Je=function(e){return e.coachOutFilter},Te=function(e){return e.coachRetFilter},ze=function(e,n){e.activeSession=n},Qe=function(e,n){e.coachOutFilter=n},Ke=function(e,n){e.coachRetFilter=n},Xe=t("a8b2"),Ze={namespaced:!0,state:qe,getters:d,mutations:f,actions:Xe},en={activeStudent:null},nn=function(e){return e.activeStudent},tn=function(e,n){e.activeStudent=n},rn=t("7f33"),on={namespaced:!0,state:en,getters:p,mutations:m,actions:rn},an={activeSession:null,resultsGCSE:null,statisticsGCSE:null,resultsALevel:null,statisticsALevel:null},un=function(e){return e.activeSession},cn=function(e){return e.resultsGCSE},sn=function(e){return e.statisticsGCSE},ln=function(e){return e.resultsALevel},dn=function(e){return e.statisticsALevel},fn=function(e,n){e.activeSession=n},pn=function(e,n){e.statisticsGCSE=n},mn=function(e,n){e.resultsGCSE=n},hn=function(e,n){e.statisticsALevel=n},bn=function(e,n){e.resultsALevel=n},gn=t("cb00"),vn={namespaced:!0,state:an,getters:h,mutations:b,actions:gn},Pn={activeSubject:null,activeYear:null,activeYearCode:null,activeExam:null},Sn=function(e){return e.activeSubject},wn=function(e){return e.activeYear},Cn=function(e){return e.activeYearCode},kn=function(e){return e.activeExam},yn=function(e,n){e.activeSubject=n},xn=function(e,n){n!==e.activeYear&&(e.activeYear=n)},An=function(e,n){n!==e.activeYearCode&&(e.activeYearCode=n)},En=function(e,n){e.activeExam=n},Dn=t("561d"),In={namespaced:!0,state:Pn,getters:g,mutations:v,actions:Dn},Mn={activeSubject:null,activeYear:null,activeYearCode:null,activeExam:null},Ln=function(e){return e.activeSubject},jn=function(e){return e.activeYear},Yn=function(e){return e.activeYearCode},On=function(e){return e.activeExam},Gn=function(e,n){e.activeSubject=n},$n=function(e,n){n!==e.activeYear&&(e.activeYear=n)},Bn=function(e,n){n!==e.activeYearCode&&(e.activeYearCode=n)},Fn=function(e,n){e.activeExam=n},Rn=t("c994"),Hn={namespaced:!0,state:Mn,getters:P,mutations:S,actions:Rn};w["a"].use(C["a"]),w["a"].use(y.a);var Un=new C["a"].Store({modules:{example:I,user:we,sockets:Re,home:_e,transport:Ze,students:on,exams:vn,hod:In,dha:Hn}});n["a"]=Un},47783:function(e,n,t){"use strict";t.d(n,"a",(function(){return a}));var r=t("8206"),o=t.n(r),a=o.a.create({baseURL:"/api/v1/public/"});o.a.defaults.port=80,n["b"]=function(e){var n=e.Vue;n.prototype.$axios=a}},"561d":function(e,n){},"71d8":function(e,n,t){"use strict";t.r(n);var r=t("93db"),o=t.n(r),a=(t("ae66"),t("df26"),t("5965")),i=t.n(a),u=(t("05e4"),t("dc4e"),t("2818"),t("9f83"),t("9415"),t("58f9"),t("9869")),c=t("2965"),s=t("c3cf"),l=t("2be8"),d=t("58c0"),f=t("57b8"),p=t("2405"),m=t("1608"),h=t("bc9f"),b=t("a182"),g=t("d835");u["a"].use(s["a"],{config:{notify:{}},iconSet:c["a"],directives:{Ripple:l["a"],ClosePopup:d["a"],Mutation:f["a"]},plugins:{Notify:p["a"],Dialog:m["a"],LocalStorage:h["a"],AppFullscreen:b["a"],Loading:g["a"]}});var v=function(){var e=this,n=e.$createElement,t=e._self._c||n;return t("div",{attrs:{id:"q-app"}},[t("router-view")],1)},P=[],S=t("915b"),w={name:"App",data(){return{dark:!1}},methods:{},computed:{},created(){S["d"].commercialLicense=!0}},C=w,k=t("2be6"),y=Object(k["a"])(C,v,P,!1,null,null,null),x=y.exports,A=t("452d"),E=t("5f2b"),D={path:"admin",component:function(){return Promise.all([t.e(0),t.e(1),t.e(54)]).then(t.bind(null,"6f3e7"))},children:[{path:"logs",component:function(){return Promise.all([t.e(0),t.e(1),t.e(43)]).then(t.bind(null,"4f9b"))}},{path:"active",component:function(){return Promise.all([t.e(0),t.e(25)]).then(t.bind(null,"282e"))}},{path:"sync",component:function(){return Promise.all([t.e(0),t.e(1),t.e(14)]).then(t.bind(null,"8976"))}},{path:"jobs",component:function(){return Promise.all([t.e(0),t.e(1),t.e(26)]).then(t.bind(null,"8426"))}},{path:"school",component:function(){return Promise.all([t.e(0),t.e(1),t.e(22)]).then(t.bind(null,"f840"))}},{path:"access",component:function(){return Promise.all([t.e(0),t.e(1),t.e(13)]).then(t.bind(null,"e5bb"))}},{path:"tags",component:function(){return Promise.all([t.e(0),t.e(1),t.e(27)]).then(t.bind(null,"9de1"))}},{path:"bandwidth",component:function(){return Promise.all([t.e(0),t.e(1),t.e(42)]).then(t.bind(null,"0553f"))}}]},I={path:"home",component:function(){return Promise.all([t.e(0),t.e(1),t.e(3)]).then(t.bind(null,"d9b1"))},children:[{path:"events",component:function(){return Promise.all([t.e(0),t.e(1),t.e(18)]).then(t.bind(null,"c818"))}},{path:"absences",component:function(){return Promise.all([t.e(0),t.e(1),t.e(31)]).then(t.bind(null,"71f0"))}},{path:"classes",component:function(){return Promise.all([t.e(0),t.e(1),t.e(32)]).then(t.bind(null,"f520"))}}]},M={path:"transport",component:function(){return Promise.all([t.e(0),t.e(1),t.e(78)]).then(t.bind(null,"4ece"))},children:[{path:"coaches",component:function(){return Promise.all([t.e(0),t.e(1),t.e(12)]).then(t.bind(null,"e1b0"))}},{path:"taxis",component:function(){return Promise.all([t.e(0),t.e(1),t.e(8)]).then(t.bind(null,"7697"))}},{path:"settings",component:function(){return Promise.all([t.e(0),t.e(1),t.e(16)]).then(t.bind(null,"3bfd"))}},{path:"portal",component:function(){return Promise.all([t.e(0),t.e(1),t.e(79)]).then(t.bind(null,"9005"))}}]},L={path:"students",component:function(){return Promise.all([t.e(0),t.e(1),t.e(77)]).then(t.bind(null,"dce4"))},children:[{path:"profile",component:function(){return Promise.all([t.e(0),t.e(1),t.e(20)]).then(t.bind(null,"bea3"))}},{path:"list",component:function(){return Promise.all([t.e(0),t.e(1),t.e(48)]).then(t.bind(null,"9fdc"))}}]},j={path:"lab",component:function(){return Promise.all([t.e(0),t.e(1),t.e(71)]).then(t.bind(null,"2698"))},children:[{path:"hot",component:function(){return Promise.all([t.e(0),t.e(1),t.e(35)]).then(t.bind(null,"2d84"))}},{path:"crud",component:function(){return Promise.all([t.e(0),t.e(1),t.e(15)]).then(t.bind(null,"151f"))}},{path:"sockets",component:function(){return Promise.all([t.e(0),t.e(1),t.e(46)]).then(t.bind(null,"0c4f"))}},{path:"reports",component:function(){return Promise.all([t.e(0),t.e(1),t.e(19)]).then(t.bind(null,"388f"))}},{path:"email",component:function(){return Promise.all([t.e(0),t.e(72)]).then(t.bind(null,"77b43"))}},{path:"tags",component:function(){return t.e(73).then(t.bind(null,"3bd1"))}},{path:"students",component:function(){return Promise.all([t.e(0),t.e(1),t.e(47)]).then(t.bind(null,"28f2"))}}]},Y={path:"watch",component:function(){return Promise.all([t.e(0),t.e(1),t.e(80)]).then(t.bind(null,"d047"))},children:[{path:"exgarde",component:function(){return Promise.all([t.e(0),t.e(1),t.e(21)]).then(t.bind(null,"6a9f"))}},{path:"gym",component:function(){return Promise.all([t.e(0),t.e(1),t.e(34)]).then(t.bind(null,"11b0"))}}]},O={path:"academic",component:function(){return Promise.all([t.e(0),t.e(1),t.e(52)]).then(t.bind(null,"7b56"))},children:[{path:"secretarial",component:function(){return Promise.all([t.e(0),t.e(1),t.e(9)]).then(t.bind(null,"3953"))}}]},G={path:"exams",component:function(){return Promise.all([t.e(0),t.e(1),t.e(62)]).then(t.bind(null,"4bb3"))},children:[{path:"",component:function(){return Promise.all([t.e(0),t.e(63)]).then(t.bind(null,"785a"))}},{path:"gcse",component:function(){return Promise.all([t.e(0),t.e(1),t.e(11)]).then(t.bind(null,"2457"))}},{path:"alevel",component:function(){return Promise.all([t.e(0),t.e(1),t.e(10)]).then(t.bind(null,"a4c7"))}},{path:"admin",component:function(){return Promise.all([t.e(0),t.e(1),t.e(23)]).then(t.bind(null,"2a8f"))}}]},$={path:"accounts",component:function(){return Promise.all([t.e(0),t.e(1),t.e(53)]).then(t.bind(null,"0cb4"))},children:[]},B={path:"hm",component:function(){return Promise.all([t.e(0),t.e(1),t.e(64)]).then(t.bind(null,"27c4"))},children:[{path:"",component:function(){return Promise.all([t.e(0),t.e(1),t.e(66)]).then(t.bind(null,"4eb7"))}},{path:"notes",component:function(){return Promise.all([t.e(0),t.e(68)]).then(t.bind(null,"72df"))}},{path:"locations",component:function(){return Promise.all([t.e(0),t.e(1),t.e(67)]).then(t.bind(null,"0c80"))}},{path:"students",component:function(){return Promise.all([t.e(0),t.e(1),t.e(69)]).then(t.bind(null,"7336"))}},{path:"covid",component:function(){return Promise.all([t.e(0),t.e(1),t.e(65)]).then(t.bind(null,"2914"))}}]},F={path:"hod",component:function(){return Promise.all([t.e(0),t.e(1),t.e(70)]).then(t.bind(null,"5549"))},children:[{path:"metrics",component:function(){return Promise.all([t.e(0),t.e(1),t.e(24)]).then(t.bind(null,"f0d1"))}},{path:"meetings",component:function(){return Promise.all([t.e(0),t.e(1),t.e(29)]).then(t.bind(null,"d497"))}},{path:"science",component:function(){return Promise.all([t.e(0),t.e(1),t.e(30)]).then(t.bind(null,"17b6"))}},{path:"lab",component:function(){return Promise.all([t.e(0),t.e(1),t.e(28)]).then(t.bind(null,"8cbd"))}}]},R={path:"dha",component:function(){return Promise.all([t.e(0),t.e(1),t.e(58)]).then(t.bind(null,"1004"))},children:[{path:"access",component:function(){return Promise.all([t.e(0),t.e(1),t.e(59)]).then(t.bind(null,"c564"))}},{path:"tags",component:function(){return Promise.all([t.e(0),t.e(1),t.e(44)]).then(t.bind(null,"90b2e"))}},{path:"wyaps",component:function(){return Promise.all([t.e(0),t.e(1),t.e(45)]).then(t.bind(null,"61dc"))}},{path:"ucas",component:function(){return Promise.all([t.e(0),t.e(61)]).then(t.bind(null,"0733"))}},{path:"baseline",component:function(){return Promise.all([t.e(0),t.e(1),t.e(60)]).then(t.bind(null,"2d78"))}}]},H={path:"smt",component:function(){return Promise.all([t.e(0),t.e(1),t.e(75)]).then(t.bind(null,"2382"))},children:[{path:"watch",component:function(){return Promise.all([t.e(0),t.e(1),t.e(33)]).then(t.bind(null,"854d"))}},{path:"covid",component:function(){return Promise.all([t.e(0),t.e(1),t.e(76)]).then(t.bind(null,"5fcd"))}}]},U={path:"bookings",component:function(){return Promise.all([t.e(0),t.e(1),t.e(55)]).then(t.bind(null,"acfe"))},children:[{path:"",component:function(){return Promise.all([t.e(0),t.e(1),t.e(56)]).then(t.bind(null,"2e557"))}}]},N={path:"sen",component:function(){return Promise.all([t.e(0),t.e(1),t.e(74)]).then(t.bind(null,"72d0"))},children:[{path:"reports",component:function(){return Promise.all([t.e(0),t.e(1),t.e(4)]).then(t.bind(null,"c1c4"))}},{path:"",component:function(){return Promise.all([t.e(0),t.e(1),t.e(4)]).then(t.bind(null,"c1c4"))}}]},V={path:"covid",component:function(){return Promise.all([t.e(0),t.e(1),t.e(57)]).then(t.bind(null,"3393"))},children:[{path:"",component:function(){return Promise.all([t.e(0),t.e(1),t.e(2)]).then(t.bind(null,"2ede"))}},{path:"responses",component:function(){return Promise.all([t.e(0),t.e(1),t.e(2)]).then(t.bind(null,"2ede"))}}]},_=[{path:"/",component:function(){return Promise.all([t.e(0),t.e(1),t.e(17)]).then(t.bind(null,"5b6e"))},children:[{path:"",component:function(){return Promise.all([t.e(0),t.e(1),t.e(3)]).then(t.bind(null,"d9b1"))}},{path:"user",component:function(){return Promise.all([t.e(0),t.e(81)]).then(t.bind(null,"cd2c"))}},D,M,L,j,O,Y,G,$,B,F,R,H,I,U,N,V]},{path:"/login",name:"login",component:function(){return Promise.all([t.e(0),t.e(41)]).then(t.bind(null,"902c3"))},children:[{path:"",component:function(){return Promise.all([t.e(0),t.e(49)]).then(t.bind(null,"cd02"))}}]},{path:"/aux",name:"aux",component:function(){return Promise.all([t.e(0),t.e(40)]).then(t.bind(null,"22f4"))},children:[{path:"bookings/coach/:id",component:function(){return Promise.all([t.e(0),t.e(36)]).then(t.bind(null,"edef"))}},{path:"server",component:function(){return Promise.all([t.e(0),t.e(1),t.e(51)]).then(t.bind(null,"9f1b"))}},{path:"covid/staff",component:function(){return Promise.all([t.e(0),t.e(1),t.e(37)]).then(t.bind(null,"e6d3"))}},{path:"covid/students",component:function(){return Promise.all([t.e(0),t.e(1),t.e(38)]).then(t.bind(null,"aacb"))}},{path:"pupils/exams",component:function(){return Promise.all([t.e(0),t.e(39)]).then(t.bind(null,"dd7c"))}}]},{path:"*",component:function(){return Promise.all([t.e(0),t.e(50)]).then(t.bind(null,"2d51"))}}],q=_;u["a"].use(E["a"]);var W=new E["a"]({mode:"history",base:"/",scrollBehavior:function(){return{y:0}},routes:q}),J=W,T=function(){return z.apply(this,arguments)};function z(){return z=i()(o.a.mark((function e(){var n,t,r;return o.a.wrap((function(e){while(1)switch(e.prev=e.next){case 0:if("function"!==typeof A["a"]){e.next=6;break}return e.next=3,Object(A["a"])({Vue:u["a"]});case 3:e.t0=e.sent,e.next=7;break;case 6:e.t0=A["a"];case 7:if(n=e.t0,"function"!==typeof J){e.next=14;break}return e.next=11,J({Vue:u["a"],store:n});case 11:e.t1=e.sent,e.next=15;break;case 14:e.t1=J;case 15:return t=e.t1,n.$router=t,r={router:t,store:n,render:function(e){return e(x)}},r.el="#q-app",e.abrupt("return",{app:r,store:n,router:t});case 20:case"end":return e.stop()}}),e)}))),z.apply(this,arguments)}var Q=t("47783"),K=function(e){var n=e.router;e.store,e.Vue;n.beforeEach((function(e,n,t){t()}))},X=(t("0ee2"),t("73e6"),t("7753"),t("b110"),t("8bb9"),t("cf18"),function(e){e.app,e.router,e.Vue}),Z=t("5871"),ee=t.n(Z),ne=function(e){e.app,e.router;var n=e.Vue;n.use(ee.a)},te=t("fa94"),re=t.n(te),oe=function(e){var n=e.Vue;n.use(re.a)},ae=(t("4fb0"),t("632c"),t("2e73"),t("76d0"),t("cd05"),t("7f3a")),ie=t.n(ae),ue=t("d82b"),ce={methods:{$parseOptions(e,n){e=e.filter((function(e){return e[n]}));var t=ie()(new Set(e.map((function(e){return e[n]})))),r=t.map((function(e){return{label:e,value:e}}));function o(e,n){var t=e.label?e.label.toUpperCase():null,r=n.label.toUpperCase(),o=0;return t>r?o=1:t<r&&(o=-1),o}return r.sort(o),r},$parseOptions2D(e,n,t){var r=ie()(new Set(e.map((function(e){return e[n][t]})))),o=r.map((function(e){return{label:e,value:e}}));function a(e,n){var t=e.label.toUpperCase(),r=n.label.toUpperCase(),o=0;return t>r?o=1:t<r&&(o=-1),o}return o.sort(a),o},$downloadBlob(e,n){this.$axios({url:e,method:"GET",responseType:"blob"}).then((function(e){var t=window.URL.createObjectURL(new Blob([e.data])),r=document.createElement("a");r.href=t,r.setAttribute("download",n),document.body.appendChild(r),r.click()}))},$errorHandler(e){this.loading=!1,console.error(e),e&&e.response&&e.response.data&&this.$q.dialog({title:"Error",message:e.response.data.message,class:"bd-font bg-primary",ok:{push:!0,color:"accent",outline:!0}}).onOk((function(){})).onCancel((function(){})).onDismiss((function(){}))},$palette(e){var n=["cyan-9","pink","blue","green","orange-10"];return e%=5,n[e]},$hexPalette(e){var n=["#f4511D","#4285f4","#098043","#3f51b5","#8e24aa","#d50201","#616161"];return e%=7,n[e]},$compare(e){var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"asc";return function(t,r){if(!t.hasOwnProperty(e)||!r.hasOwnProperty(e))return 0;var o="string"===typeof t[e]?t[e].toUpperCase():t[e],a="string"===typeof r[e]?r[e].toUpperCase():r[e],i=0;return o>a?i=1:o<a&&(i=-1),"desc"===n?-1*i:i}},$date(e){if(!e)return new Date;var n=e.split("/");return new Date(+n[2],n[1]-1,+n[0])},$shortDate(e){if(!e)return new Date;var n=e.split("/"),t=new Date(+n[2],n[1]-1,+n[0]);return ue["a"].formatDate(t,"MMM D")},$cloneArray(e){return JSON.parse(JSON.stringify(e))},$startLoading(){this.$q.loading.show()},$endLoading(){this.$q.loading.hide()}}},se={methods:{$wampSubscribe(e,n){this.$wamp.subscribe(e,(function(e,t,r){n(t)}),{acknowledge:!0}).then((function(n){console.warn("Subscribing",e),this.wampSubscription=n}))},$wampPublish(e,n,t){console.warn("Publishing",e),this.$wamp.publish(e,[],n,{exclude_me:!t})}}};u["a"].mixin(ce),u["a"].mixin(se);var le=t("1c0c");function de(){return fe.apply(this,arguments)}function fe(){return fe=i()(o.a.mark((function e(){var n,t,r,a,i,c,s,l,d;return o.a.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,T();case 2:n=e.sent,t=n.app,r=n.store,a=n.router,i=!0,c=function(e){i=!1,window.location.href=e},s=window.location.href.replace(window.location.origin,""),l=[Q["b"],K,X,ne,oe,void 0,le["default"]],d=0;case 11:if(!(!0===i&&d<l.length)){e.next=29;break}if("function"===typeof l[d]){e.next=14;break}return e.abrupt("continue",26);case 14:return e.prev=14,e.next=17,l[d]({app:t,router:a,store:r,Vue:u["a"],ssrContext:null,redirect:c,urlPath:s});case 17:e.next=26;break;case 19:if(e.prev=19,e.t0=e["catch"](14),!e.t0||!e.t0.url){e.next=24;break}return window.location.href=e.t0.url,e.abrupt("return");case 24:return console.error("[Quasar] boot error:",e.t0),e.abrupt("return");case 26:d++,e.next=11;break;case 29:if(!1!==i){e.next=31;break}return e.abrupt("return");case 31:new u["a"](t);case 32:case"end":return e.stop()}}),e,null,[[14,19]])}))),fe.apply(this,arguments)}de()},"7f33":function(e,n){},9415:function(e,n,t){},"9f83":function(e,n,t){},a8b2:function(e,n){},bf1d:function(e,n){},c994:function(e,n){},cb00:function(e,n){},f1a8:function(e,n){},fe3b:function(e,n){}},[[1,6,0]]]);