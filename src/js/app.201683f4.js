(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[5],{"0d48":function(e,n){},1:function(e,n,t){e.exports=t("71d8")},"452d":function(e,n,t){"use strict";var r={};t.r(r),t.d(r,"userId",(function(){return M})),t.d(r,"name",(function(){return E})),t.d(r,"permissions",(function(){return F})),t.d(r,"isDarkMode",(function(){return L})),t.d(r,"isAuthenticated",(function(){return j})),t.d(r,"getModuleAccess",(function(){return G})),t.d(r,"getModuleColor",(function(){return O})),t.d(r,"getPageAccess",(function(){return B})),t.d(r,"menuIsOpen",(function(){return R})),t.d(r,"currentModule",(function(){return H})),t.d(r,"getGlobalSubject",(function(){return $})),t.d(r,"getGlobalStudent",(function(){return U})),t.d(r,"getGlobalHouse",(function(){return N}));var o={};t.r(o),t.d(o,"authUser",(function(){return J})),t.d(o,"permissions",(function(){return Q})),t.d(o,"roles",(function(){return K})),t.d(o,"clearAuthData",(function(){return X})),t.d(o,"isDarkMode",(function(){return Z})),t.d(o,"role",(function(){return ee})),t.d(o,"toggleMenu",(function(){return ne})),t.d(o,"setModule",(function(){return te})),t.d(o,"setGlobalStudent",(function(){return re})),t.d(o,"setGlobalSubject",(function(){return oe})),t.d(o,"setGlobalHouse",(function(){return ae}));var a={};t.r(a),t.d(a,"login",(function(){return ue})),t.d(a,"fetchPermissions",(function(){return se})),t.d(a,"tryAutoLogin",(function(){return ce})),t.d(a,"logout",(function(){return le})),t.d(a,"isDarkMode",(function(){return de})),t.d(a,"setAxiosHeader",(function(){return fe})),t.d(a,"setPrimaryColor",(function(){return pe})),t.d(a,"toggleMenu",(function(){return me})),t.d(a,"setModule",(function(){return he})),t.d(a,"setGlobalStudent",(function(){return be})),t.d(a,"setGlobalHouse",(function(){return ge})),t.d(a,"setGlobalSubject",(function(){return ve}));var i={};t.r(i),t.d(i,"isConnected",(function(){return we})),t.d(i,"consoleLog",(function(){return ke})),t.d(i,"updaterData",(function(){return Ce})),t.d(i,"updater",(function(){return ye})),t.d(i,"progress",(function(){return xe})),t.d(i,"progressMessage",(function(){return Ae}));var u={};t.r(u),t.d(u,"consoleLog",(function(){return Ie})),t.d(u,"updater",(function(){return Me})),t.d(u,"notify",(function(){return Ee})),t.d(u,"progress",(function(){return Fe})),t.d(u,"clearConsoleLog",(function(){return Le})),t.d(u,"isConnected",(function(){return je}));var s={};t.r(s),t.d(s,"connectSocket",(function(){return Oe})),t.d(s,"clearConsoleLog",(function(){return Be}));var c={};t.r(c),t.d(c,"activeClass",(function(){return $e}));var l={};t.r(l),t.d(l,"setActiveClass",(function(){return Ue}));var d={};t.r(d),t.d(d,"activeSession",(function(){return Ye})),t.d(d,"coachOutFilter",(function(){return We})),t.d(d,"coachRetFilter",(function(){return qe}));var f={};t.r(f),t.d(f,"setActiveSession",(function(){return Te})),t.d(f,"setCoachOutFilter",(function(){return ze})),t.d(f,"setCoachRetFilter",(function(){return Je}));var p={};t.r(p),t.d(p,"activeStudent",(function(){return Ze}));var m={};t.r(m),t.d(m,"setActiveStudent",(function(){return en}));var h={};t.r(h),t.d(h,"activeSession",(function(){return on})),t.d(h,"resultsGCSE",(function(){return an})),t.d(h,"statisticsGCSE",(function(){return un})),t.d(h,"resultsALevel",(function(){return sn})),t.d(h,"statisticsALevel",(function(){return cn}));var b={};t.r(b),t.d(b,"setActiveSession",(function(){return ln})),t.d(b,"setStatisticsGCSE",(function(){return dn})),t.d(b,"setResultsGCSE",(function(){return fn})),t.d(b,"setStatisticsALevel",(function(){return pn})),t.d(b,"setResultsALevel",(function(){return mn}));var g={};t.r(g),t.d(g,"activeSubject",(function(){return vn})),t.d(g,"activeYear",(function(){return Pn})),t.d(g,"activeExam",(function(){return Sn}));var v={};t.r(v),t.d(v,"setActiveSubject",(function(){return wn})),t.d(v,"setActiveYear",(function(){return kn})),t.d(v,"setActiveExam",(function(){return Cn}));var P=t("9869"),S=t("9ce4"),w=t("fa94"),k=t.n(w),C={},y=t("fe3b"),x=t("bf1d"),A=t("f1a8"),D={namespaced:!0,state:C,getters:y,mutations:x,actions:A},I={userId:null,auth:null,firstname:null,lastname:null,permissions:{},roles:[],isDarkMode:!0,menuIsOpen:!0,moduleIcon:"",moduleName:"",globalSubject:null,globalStudent:null,globalHouse:null},M=function(e){return e.userId},E=function(e){var n={firstname:e.firstname,lastname:e.lastname};return n},F=function(e){return e.permissions},L=function(e){return e.isDarkMode},j=function(e){return null!==e.auth},G=function(e){return function(n){return!!e.permissions[n]&&e.permissions[n].hasAccess}},O=function(e){return function(n){return e.permissions[n]?e.permissions[n].color:"#4fc08d"}},B=function(e){return function(n,t){if(!e.permissions[n])return!1;var r=e.permissions[n];return!!r.pages[t]&&r.pages[t].hasAccess}},R=function(e){return e.menuIsOpen},H=function(e){return{icon:e.moduleIcon,name:e.moduleName}},$=function(e){return e.globalSubject},U=function(e){return e.globalStudent},N=function(e){return e.globalHouse},V=(t("c880"),t("3cc3")),_=t.n(V),Y=(t("2e73"),t("76d0"),t("0e20"),t("bc9f")),W=t("156f"),q=t("134d"),T={neutral:"#E0E1E2",positive:"#d242ff",negative:"#DB2828",info:"#31CCEC",warning:"#f57c00",accentx:"#6A0DAD",accent:"#d242ff",accentSecondary:"#00838f",primary:"#FFFFFF",secondary:"#FFFFFF",tertiary:"#FFFFFF",font:"#121212",fontSecondary:"#636363",fontNegative:"#E5E5E5"},z={neutral:"#E0E1E2",positive:"#c6ff00",negative:"#DB2828",info:"#31CCEC",warning:"#EC300D",accent:"#4ce8f6",accentSecondary:"#00838f",primary:"#181d23",secondary:"#1c252c",tertiary:"#262f37",font:"#e5e5e5",fontSecondary:"#9e9e9e",fontNegative:"#121212"},J=function(e,n){e.auth=n.auth,e.userId=n.userId,e.permissions=n.permissions,e.roles=n.roles,e.globalSubject=n.globalSubject,e.globalStudent=n.globalStudent,e.globalHouse=n.globalHouse},Q=function(e,n){e.permissions=n},K=function(e,n){e.roles=n},X=function(e){e.auth=null,e.userId=null,e.permissions={},e.roles=[]},Z=function(e,n){e.isDarkMode=n,Y["a"].set("isDarkMode",n);var t=n?z:T;q["a"].setBrand("neutral",t.neutral),q["a"].setBrand("positive",t.positive),q["a"].setBrand("negative",t.negative),q["a"].setBrand("into",t.info),q["a"].setBrand("warning",t.warning),q["a"].setBrand("accent",t.accent),q["a"].setBrand("accent-secondary",t.accentSecondary),q["a"].setBrand("primary",t.primary),q["a"].setBrand("secondary",t.secondary),q["a"].setBrand("tertiary",t.tertiary),q["a"].setBrand("font",t.font),q["a"].setBrand("font-secondary",t.fontSecondary),q["a"].setBrand("font-negative",t.fontNegative),W["a"].set(n)},ee=function(e,n){var t=n[0].data,r=n[0].roleId;Object.entries(t).forEach((function(n){var o=_()(n,2),a=o[0],i=o[1];if(e.permissions[a]){!0===t[a].hasAccess&&(e.permissions[a].hasAccess=!0,e.permissions[a].fromRoles.find((function(e){return e===r}))||e.permissions[a].fromRoles.push(r)),!1===t[a].hasAccess&&1===e.permissions[a].fromRoles.length&&(e.permissions[a].fromRoles=[],e.permissions[a].hasAccess=!1);var u=i.pages;Object.entries(u).forEach((function(n){var t=_()(n,2),o=t[0],i=t[1];e.permissions[a].pages[o]?e.permissions[a].pages[o].fromRoles.find((function(e){return e===r}))?1===e.permissions[a].pages[o].fromRoles.length&&P["a"].set(e.permissions[a].pages,o,i):!0===i.hasAccess&&(e.permissions[a].pages[o].hasAccess=!0,e.permissions[a].pages[o].fromRoles.push(r)):P["a"].set(e.permissions[a].pages,o,i)}))}else P["a"].set(e.permissions,a,i)})),Y["a"].set("permissions",e.permissions)},ne=function(e,n){e.menuIsOpen=n||!e.menuIsOpen},te=function(e,n){e.moduleIcon=n.icon,e.moduleName=n.name},re=function(e,n){e.globalStudent=n},oe=function(e,n){e.globalSubject=n},ae=function(e,n){e.globalHouse=n},ie=(t("c8a0"),t("47783")),ue=function(e,n){var t=e.commit,r=e.dispatch;return new Promise((function(e,o){ie["a"].post("/auth/login",{login:n.login,password:n.password}).then((function(n){var o=n.data.loginObject;!0===n.data.success&&(t("authUser",{auth:o.auth,userId:o.userId,permissions:o.permissions,roles:o.roles,isDarkMode:o.isDarkMode}),Y["a"].set("auth",o.auth),Y["a"].set("userId",o.userId),Y["a"].set("permissions",o.permissions),Y["a"].set("roles",o.roles),Y["a"].set("isDarkMode",o.isDarkMode),r("setAxiosHeader",o.auth),r("sockets/connectSocket",o.auth,{root:!0}),r("isDarkMode",o.isDarkMode)),e(n.data)})).catch((function(e){o(e)}))}))},se=function(e){var n=e.commit;e.dispatch;return new Promise((function(e,t){ie["a"].get("/auth/permissions").then((function(t){var r=t.data.permissions,o=t.data.roles;r&&(n("permissions",r),n("roles",o),Y["a"].set("permissions",r),Y["a"].set("roles",o)),e(t.data)})).catch((function(e){t(e)}))}))},ce=function(e){var n=e.commit,t=e.dispatch,r=Y["a"].getItem("auth");return null!==r&&(n("authUser",{auth:r,userId:Y["a"].getItem("userId"),permissions:Y["a"].getItem("permissions"),roles:Y["a"].getItem("roles"),isDarkMode:Y["a"].getItem("isDarkMode"),globalStudent:Y["a"].getItem("globalStudent"),globalSubject:Y["a"].getItem("globalSubject"),globalHouse:Y["a"].getItem("globalHouse")}),t("isDarkMode",Y["a"].getItem("isDarkMode")),t("setAxiosHeader",r),t("sockets/connectSocket",r,{root:!0}),t("fetchPermissions",r),!0)},le=function(e){var n=e.commit,t=e.dispatch;Y["a"].clear(),n("clearAuthData"),t("setAxiosHeader","")},de=function(e,n){var t=e.commit,r=e.state;t("isDarkMode",n),ie["a"].post("/auth/login/dark",{userId:r.userId,isDarkMode:n})},fe=function(e,n){ie["a"].defaults.headers.common["Authorization"]=n},pe=function(e,n){e.commit;return n===q["a"]},me=function(e){var n=e.commit;n("toggleMenu")},he=function(e,n){var t=e.commit;t("setModule",n)},be=function(e,n){var t=e.commit;Y["a"].set("globalStudent",n),t("setGlobalStudent",n)},ge=function(e,n){var t=e.commit;Y["a"].set("globalHouse",n),t("setGlobalHouse",n)},ve=function(e,n){var t=e.commit;console.log(n),Y["a"].set("globalSubject",n),t("setGlobalSubject",n)},Pe={namespaced:!0,state:I,getters:r,mutations:o,actions:a},Se={isConnected:!1,consoleLog:[],updaters:{},updaterData:{},progress:{},progressMessage:{},progressIsComplete:{}},we=function(e){return e.isConnected},ke=function(e){return e.consoleLog.slice().reverse()},Ce=function(e){return function(n){return e.updaterData[n]}},ye=function(e){return function(n,t){if(!e.updaters[n])return t;var r=e.updaters[n],o=r.key,a=r.data[o],i=t.findIndex((function(e){return e[o]===a}));return i>-1&&P["a"].set(t,i,r.data),t}},xe=function(e){return function(n){return e.progress[n]?e.progress[n]:0}},Ae=function(e){return function(n){return e.progressMessage[n]?e.progressMessage[n]:""}},De=(t("ae66"),t("2405")),Ie=function(e,n){if(!0===n.replace){for(var t=e.consoleLog.length,r=t-1;r>=0;r--)if(!1===e.consoleLog[r].isError){e.consoleLog[r].message=n.message;break}}else e.consoleLog.push(n)},Me=function(e,n){var t=n[0].updaterId;P["a"].set(e.updaters,t,n[0])},Ee=function(e,n){console.log("NOTIFY",n),De["a"].create({message:n[0].message,color:"primary",textColor:"black"})},Fe=function(e,n){var t=n[0].progressId;P["a"].set(e.progress,t,n[0].progress),n[0].message&&n[0].message.length>0&&P["a"].set(e.progressMessage,t,n[0].message),P["a"].set(e.progressIsComplete,t,n[0].isComplete)},Le=function(e){e.consoleLog=[]},je=function(e,n){e.isConnected=n},Ge=t("0c9c"),Oe=function(e,n){var t=e.commit,r=e.rootState;P["a"].use(Ge["a"],{debug:!0,url:"wss://adazmq.marlboroughcollege.org/wss",realm:"realm1",onopen:function(e,n){t("isConnected",!0)},onclose:function(e,n){t("isConnected",!1)}}),n&&(P["a"].Wamp.subscribe("console_"+n,(function(e,n,r){t("consoleLog",e[0])}),{acknowledge:!0}).then((function(e){})),P["a"].Wamp.subscribe("updater_"+n,(function(e,n,r){t("updater",e)}),{acknowledge:!0}).then((function(e){})),P["a"].Wamp.subscribe("progress_"+n,(function(e,n,r){t("progress",e)}),{acknowledge:!0}).then((function(e){})),P["a"].Wamp.subscribe("notify_"+n,(function(e,n,r){t("notify",e)}),{acknowledge:!0}).then((function(e){})),r.user.roles.forEach((function(e,n){console.log(e),P["a"].Wamp.subscribe("role_"+e,(function(e,n,r){t("user/role",e,{root:!0})}),{acknowledge:!0}).then((function(e){}))})))},Be=function(e){var n=e.commit;n("clearConsoleLog")},Re={namespaced:!0,state:Se,getters:i,mutations:u,actions:s},He={activeClass:null},$e=function(e){return e.activeClass},Ue=function(e,n){e.activeClass=n},Ne=t("0d48"),Ve={namespaced:!0,state:He,getters:c,mutations:l,actions:Ne},_e={activeSession:null,activeSessionName:null,coachOutFilter:[],coachRetFilter:[]},Ye=function(e){return e.activeSession},We=function(e){return e.coachOutFilter},qe=function(e){return e.coachRetFilter},Te=function(e,n){e.activeSession=n},ze=function(e,n){e.coachOutFilter=n},Je=function(e,n){e.coachRetFilter=n},Qe=t("a8b2"),Ke={namespaced:!0,state:_e,getters:d,mutations:f,actions:Qe},Xe={activeStudent:null},Ze=function(e){return e.activeStudent},en=function(e,n){e.activeStudent=n},nn=t("7f33"),tn={namespaced:!0,state:Xe,getters:p,mutations:m,actions:nn},rn={activeSession:null,resultsGCSE:null,statisticsGCSE:null,resultsALevel:null,statisticsALevel:null},on=function(e){return e.activeSession},an=function(e){return e.resultsGCSE},un=function(e){return e.statisticsGCSE},sn=function(e){return e.resultsALevel},cn=function(e){return e.statisticsALevel},ln=function(e,n){e.activeSession=n},dn=function(e,n){e.statisticsGCSE=n},fn=function(e,n){e.resultsGCSE=n},pn=function(e,n){e.statisticsALevel=n},mn=function(e,n){e.resultsALevel=n},hn=t("cb00"),bn={namespaced:!0,state:rn,getters:h,mutations:b,actions:hn},gn={activeSubject:null,activeYear:null,activeExam:null},vn=function(e){return e.activeSubject},Pn=function(e){return e.activeYear},Sn=function(e){return e.activeExam},wn=function(e,n){e.activeSubject=n},kn=function(e,n){n!==e.activeYear&&(e.activeYear=n)},Cn=function(e,n){e.activeExam=n},yn=t("561d"),xn={namespaced:!0,state:gn,getters:g,mutations:v,actions:yn};P["a"].use(S["a"]),P["a"].use(k.a);var An=new S["a"].Store({modules:{example:D,user:Pe,sockets:Re,home:Ve,transport:Ke,students:tn,exams:bn,hod:xn}});n["a"]=An},47783:function(e,n,t){"use strict";t.d(n,"a",(function(){return a}));var r=t("8206"),o=t.n(r),a=o.a.create({baseURL:"/api/v1/public/"});o.a.defaults.port=80,n["b"]=function(e){var n=e.Vue;n.prototype.$axios=a}},"561d":function(e,n){},"71d8":function(e,n,t){"use strict";t.r(n);var r=t("93db"),o=t.n(r),a=(t("ae66"),t("df26"),t("5965")),i=t.n(a),u=(t("05e4"),t("dc4e"),t("2818"),t("9f83"),t("9415"),t("58f9"),t("9869")),s=t("2965"),c=t("c3cf"),l=t("2be8"),d=t("58c0"),f=t("57b8"),p=t("2405"),m=t("1608"),h=t("bc9f"),b=t("a182"),g=t("d835");u["a"].use(c["a"],{config:{notify:{}},iconSet:s["a"],directives:{Ripple:l["a"],ClosePopup:d["a"],Mutation:f["a"]},plugins:{Notify:p["a"],Dialog:m["a"],LocalStorage:h["a"],AppFullscreen:b["a"],Loading:g["a"]}});var v=function(){var e=this,n=e.$createElement,t=e._self._c||n;return t("div",{attrs:{id:"q-app"}},[t("router-view")],1)},P=[],S=t("915b"),w={name:"App",data(){return{dark:!1}},methods:{},computed:{},created(){S["d"].commercialLicense=!0}},k=w,C=t("2be6"),y=Object(C["a"])(k,v,P,!1,null,null,null),x=y.exports,A=t("452d"),D=t("5f2b"),I={path:"admin",component:function(){return Promise.all([t.e(0),t.e(1),t.e(50)]).then(t.bind(null,"6f3e"))},children:[{path:"logs",component:function(){return Promise.all([t.e(0),t.e(1),t.e(40)]).then(t.bind(null,"4f9b"))}},{path:"active",component:function(){return Promise.all([t.e(0),t.e(24)]).then(t.bind(null,"282e"))}},{path:"sync",component:function(){return Promise.all([t.e(0),t.e(1),t.e(14)]).then(t.bind(null,"8976"))}},{path:"jobs",component:function(){return Promise.all([t.e(0),t.e(1),t.e(25)]).then(t.bind(null,"8426"))}},{path:"school",component:function(){return Promise.all([t.e(0),t.e(1),t.e(41)]).then(t.bind(null,"f840"))}},{path:"access",component:function(){return Promise.all([t.e(0),t.e(1),t.e(13)]).then(t.bind(null,"e5bb"))}},{path:"tags",component:function(){return Promise.all([t.e(0),t.e(1),t.e(26)]).then(t.bind(null,"9de1"))}},{path:"bandwidth",component:function(){return Promise.all([t.e(0),t.e(1),t.e(39)]).then(t.bind(null,"0553f"))}}]},M={path:"home",component:function(){return Promise.all([t.e(0),t.e(1),t.e(3)]).then(t.bind(null,"d9b1"))},children:[{path:"events",component:function(){return Promise.all([t.e(0),t.e(1),t.e(19)]).then(t.bind(null,"c818"))}},{path:"absences",component:function(){return Promise.all([t.e(0),t.e(1),t.e(29)]).then(t.bind(null,"71f0"))}},{path:"classes",component:function(){return Promise.all([t.e(0),t.e(1),t.e(30)]).then(t.bind(null,"f520"))}}]},E={path:"transport",component:function(){return Promise.all([t.e(0),t.e(1),t.e(70)]).then(t.bind(null,"4ece"))},children:[{path:"coaches",component:function(){return Promise.all([t.e(0),t.e(1),t.e(11)]).then(t.bind(null,"e1b0"))}},{path:"taxis",component:function(){return Promise.all([t.e(0),t.e(1),t.e(8)]).then(t.bind(null,"7697"))}},{path:"settings",component:function(){return Promise.all([t.e(0),t.e(1),t.e(16)]).then(t.bind(null,"3bfd"))}},{path:"portal",component:function(){return Promise.all([t.e(0),t.e(1),t.e(71)]).then(t.bind(null,"9005"))}}]},F={path:"students",component:function(){return Promise.all([t.e(0),t.e(1),t.e(69)]).then(t.bind(null,"dce4"))},children:[{path:"profile",component:function(){return Promise.all([t.e(0),t.e(1),t.e(23)]).then(t.bind(null,"bea3"))}},{path:"list",component:function(){return Promise.all([t.e(0),t.e(1),t.e(44)]).then(t.bind(null,"9fdc"))}}]},L={path:"lab",component:function(){return Promise.all([t.e(0),t.e(1),t.e(63)]).then(t.bind(null,"2698"))},children:[{path:"hot",component:function(){return Promise.all([t.e(0),t.e(1),t.e(33)]).then(t.bind(null,"2d84"))}},{path:"crud",component:function(){return Promise.all([t.e(0),t.e(1),t.e(15)]).then(t.bind(null,"151f"))}},{path:"sockets",component:function(){return Promise.all([t.e(0),t.e(1),t.e(42)]).then(t.bind(null,"0c4f"))}},{path:"reports",component:function(){return Promise.all([t.e(0),t.e(1),t.e(20)]).then(t.bind(null,"388f"))}},{path:"email",component:function(){return Promise.all([t.e(0),t.e(64)]).then(t.bind(null,"77b43"))}},{path:"tags",component:function(){return t.e(65).then(t.bind(null,"3bd1"))}},{path:"students",component:function(){return Promise.all([t.e(0),t.e(1),t.e(43)]).then(t.bind(null,"28f2"))}}]},j={path:"watch",component:function(){return Promise.all([t.e(0),t.e(1),t.e(72)]).then(t.bind(null,"d047"))},children:[{path:"exgarde",component:function(){return Promise.all([t.e(0),t.e(1),t.e(21)]).then(t.bind(null,"6a9f"))}},{path:"gym",component:function(){return Promise.all([t.e(0),t.e(1),t.e(32)]).then(t.bind(null,"11b0"))}}]},G={path:"academic",component:function(){return Promise.all([t.e(0),t.e(1),t.e(48)]).then(t.bind(null,"7b56"))},children:[{path:"secretarial",component:function(){return Promise.all([t.e(0),t.e(1),t.e(12)]).then(t.bind(null,"3953"))}}]},O={path:"exams",component:function(){return Promise.all([t.e(0),t.e(1),t.e(54)]).then(t.bind(null,"4bb3"))},children:[{path:"",component:function(){return Promise.all([t.e(0),t.e(55)]).then(t.bind(null,"785a"))}},{path:"gcse",component:function(){return Promise.all([t.e(0),t.e(1),t.e(10)]).then(t.bind(null,"2457"))}},{path:"alevel",component:function(){return Promise.all([t.e(0),t.e(1),t.e(9)]).then(t.bind(null,"a4c7"))}},{path:"admin",component:function(){return Promise.all([t.e(0),t.e(1),t.e(22)]).then(t.bind(null,"2a8f"))}}]},B={path:"accounts",component:function(){return Promise.all([t.e(0),t.e(1),t.e(49)]).then(t.bind(null,"0cb4"))},children:[]},R={path:"hm",component:function(){return Promise.all([t.e(0),t.e(1),t.e(56)]).then(t.bind(null,"27c4"))},children:[{path:"",component:function(){return Promise.all([t.e(0),t.e(1),t.e(58)]).then(t.bind(null,"4eb7"))}},{path:"notes",component:function(){return Promise.all([t.e(0),t.e(60)]).then(t.bind(null,"72df"))}},{path:"locations",component:function(){return Promise.all([t.e(0),t.e(1),t.e(59)]).then(t.bind(null,"0c80"))}},{path:"students",component:function(){return Promise.all([t.e(0),t.e(1),t.e(61)]).then(t.bind(null,"7336"))}},{path:"covid",component:function(){return Promise.all([t.e(0),t.e(1),t.e(57)]).then(t.bind(null,"2914"))}}]},H={path:"hod",component:function(){return Promise.all([t.e(0),t.e(1),t.e(62)]).then(t.bind(null,"5549"))},children:[{path:"metrics",component:function(){return Promise.all([t.e(0),t.e(1),t.e(18)]).then(t.bind(null,"f0d1"))}},{path:"meetings",component:function(){return Promise.all([t.e(0),t.e(1),t.e(28)]).then(t.bind(null,"d497"))}},{path:"lab",component:function(){return Promise.all([t.e(0),t.e(1),t.e(27)]).then(t.bind(null,"8cbd"))}}]},$={path:"smt",component:function(){return Promise.all([t.e(0),t.e(1),t.e(67)]).then(t.bind(null,"2382"))},children:[{path:"watch",component:function(){return Promise.all([t.e(0),t.e(1),t.e(31)]).then(t.bind(null,"854d"))}},{path:"covid",component:function(){return Promise.all([t.e(0),t.e(1),t.e(68)]).then(t.bind(null,"5fcd"))}}]},U={path:"bookings",component:function(){return Promise.all([t.e(0),t.e(1),t.e(51)]).then(t.bind(null,"acfe"))},children:[{path:"",component:function(){return Promise.all([t.e(0),t.e(1),t.e(52)]).then(t.bind(null,"2e55"))}}]},N={path:"sen",component:function(){return Promise.all([t.e(0),t.e(1),t.e(66)]).then(t.bind(null,"72d0"))},children:[{path:"reports",component:function(){return Promise.all([t.e(0),t.e(1),t.e(4)]).then(t.bind(null,"c1c4"))}},{path:"",component:function(){return Promise.all([t.e(0),t.e(1),t.e(4)]).then(t.bind(null,"c1c4"))}}]},V={path:"covid",component:function(){return Promise.all([t.e(0),t.e(1),t.e(53)]).then(t.bind(null,"3393"))},children:[{path:"",component:function(){return Promise.all([t.e(0),t.e(1),t.e(2)]).then(t.bind(null,"2ede"))}},{path:"responses",component:function(){return Promise.all([t.e(0),t.e(1),t.e(2)]).then(t.bind(null,"2ede"))}}]},_=[{path:"/",component:function(){return Promise.all([t.e(0),t.e(1),t.e(17)]).then(t.bind(null,"5b6e"))},children:[{path:"",component:function(){return Promise.all([t.e(0),t.e(1),t.e(3)]).then(t.bind(null,"d9b1"))}},{path:"user",component:function(){return Promise.all([t.e(0),t.e(73)]).then(t.bind(null,"cd2c"))}},I,E,F,L,G,j,O,B,R,H,$,M,U,N,V]},{path:"/login",name:"login",component:function(){return Promise.all([t.e(0),t.e(38)]).then(t.bind(null,"902c3"))},children:[{path:"",component:function(){return Promise.all([t.e(0),t.e(45)]).then(t.bind(null,"cd02"))}}]},{path:"/aux",name:"aux",component:function(){return Promise.all([t.e(0),t.e(37)]).then(t.bind(null,"22f4"))},children:[{path:"bookings/coach/:id",component:function(){return Promise.all([t.e(0),t.e(34)]).then(t.bind(null,"edef"))}},{path:"server",component:function(){return Promise.all([t.e(0),t.e(1),t.e(47)]).then(t.bind(null,"9f1b"))}},{path:"covid/staff",component:function(){return Promise.all([t.e(0),t.e(1),t.e(35)]).then(t.bind(null,"e6d3"))}},{path:"covid/students",component:function(){return Promise.all([t.e(0),t.e(1),t.e(36)]).then(t.bind(null,"aacb"))}}]},{path:"*",component:function(){return Promise.all([t.e(0),t.e(46)]).then(t.bind(null,"2d51"))}}],Y=_;u["a"].use(D["a"]);var W=new D["a"]({mode:"history",base:"/",scrollBehavior:function(){return{y:0}},routes:Y}),q=W,T=function(){return z.apply(this,arguments)};function z(){return z=i()(o.a.mark((function e(){var n,t,r;return o.a.wrap((function(e){while(1)switch(e.prev=e.next){case 0:if("function"!==typeof A["a"]){e.next=6;break}return e.next=3,Object(A["a"])({Vue:u["a"]});case 3:e.t0=e.sent,e.next=7;break;case 6:e.t0=A["a"];case 7:if(n=e.t0,"function"!==typeof q){e.next=14;break}return e.next=11,q({Vue:u["a"],store:n});case 11:e.t1=e.sent,e.next=15;break;case 14:e.t1=q;case 15:return t=e.t1,n.$router=t,r={router:t,store:n,render:function(e){return e(x)}},r.el="#q-app",e.abrupt("return",{app:r,store:n,router:t});case 20:case"end":return e.stop()}}),e)}))),z.apply(this,arguments)}var J=t("47783"),Q=function(e){var n=e.router;e.store,e.Vue;n.beforeEach((function(e,n,t){t()}))},K=(t("0ee2"),t("73e6"),t("7753"),t("b110"),t("8bb9"),t("cf18"),function(e){e.app,e.router,e.Vue}),X=t("5871"),Z=t.n(X),ee=function(e){e.app,e.router;var n=e.Vue;n.use(Z.a)},ne=t("fa94"),te=t.n(ne),re=function(e){var n=e.Vue;n.use(te.a)},oe=(t("4fb0"),t("632c"),t("2e73"),t("76d0"),t("cd05"),t("7f3a")),ae=t.n(oe),ie=t("d82b"),ue={methods:{$parseOptions(e,n){var t=ae()(new Set(e.map((function(e){return e[n]})))),r=t.map((function(e){return{label:e,value:e}}));function o(e,n){var t=e.label.toUpperCase(),r=n.label.toUpperCase(),o=0;return t>r?o=1:t<r&&(o=-1),o}return r.sort(o),r},$parseOptions2D(e,n,t){var r=ae()(new Set(e.map((function(e){return e[n][t]})))),o=r.map((function(e){return{label:e,value:e}}));function a(e,n){var t=e.label.toUpperCase(),r=n.label.toUpperCase(),o=0;return t>r?o=1:t<r&&(o=-1),o}return o.sort(a),o},$downloadBlob(e,n){this.$axios({url:e,method:"GET",responseType:"blob"}).then((function(e){var t=window.URL.createObjectURL(new Blob([e.data])),r=document.createElement("a");r.href=t,r.setAttribute("download",n),document.body.appendChild(r),r.click()}))},$errorHandler(e){this.loading=!1,console.error(e)},$palette(e){var n=["cyan-9","pink","blue","green","orange-10"];return e%=5,n[e]},$hexPalette(e){var n=["#f4511D","#4285f4","#098043","#3f51b5","#8e24aa","#d50201","#616161"];return e%=7,n[e]},$compare(e){var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"asc";return function(t,r){if(!t.hasOwnProperty(e)||!r.hasOwnProperty(e))return 0;var o="string"===typeof t[e]?t[e].toUpperCase():t[e],a="string"===typeof r[e]?r[e].toUpperCase():r[e],i=0;return o>a?i=1:o<a&&(i=-1),"desc"===n?-1*i:i}},$date(e){if(!e)return new Date;var n=e.split("/");return new Date(+n[2],n[1]-1,+n[0])},$shortDate(e){if(!e)return new Date;var n=e.split("/"),t=new Date(+n[2],n[1]-1,+n[0]);return ie["a"].formatDate(t,"MMM D")}}},se={methods:{$wampSubscribe(e,n){this.$wamp.subscribe(e,(function(e,t,r){n(t)}),{acknowledge:!0}).then((function(n){console.warn("Subscribing",e),this.wampSubscription=n}))},$wampPublish(e,n,t){console.warn("Publishing",e),this.$wamp.publish(e,[],n,{exclude_me:!t})}}};u["a"].mixin(ue),u["a"].mixin(se);var ce=t("1c0c");function le(){return de.apply(this,arguments)}function de(){return de=i()(o.a.mark((function e(){var n,t,r,a,i,s,c,l,d;return o.a.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,T();case 2:n=e.sent,t=n.app,r=n.store,a=n.router,i=!0,s=function(e){i=!1,window.location.href=e},c=window.location.href.replace(window.location.origin,""),l=[J["b"],Q,K,ee,re,void 0,ce["default"]],d=0;case 11:if(!(!0===i&&d<l.length)){e.next=29;break}if("function"===typeof l[d]){e.next=14;break}return e.abrupt("continue",26);case 14:return e.prev=14,e.next=17,l[d]({app:t,router:a,store:r,Vue:u["a"],ssrContext:null,redirect:s,urlPath:c});case 17:e.next=26;break;case 19:if(e.prev=19,e.t0=e["catch"](14),!e.t0||!e.t0.url){e.next=24;break}return window.location.href=e.t0.url,e.abrupt("return");case 24:return console.error("[Quasar] boot error:",e.t0),e.abrupt("return");case 26:d++,e.next=11;break;case 29:if(!1!==i){e.next=31;break}return e.abrupt("return");case 31:new u["a"](t);case 32:case"end":return e.stop()}}),e,null,[[14,19]])}))),de.apply(this,arguments)}le()},"7f33":function(e,n){},9415:function(e,n,t){},"9f83":function(e,n,t){},a8b2:function(e,n){},bf1d:function(e,n){},cb00:function(e,n){},f1a8:function(e,n){},fe3b:function(e,n){}},[[1,6,0]]]);