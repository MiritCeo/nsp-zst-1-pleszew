import{z as d,A as c,o as p,e as u,b as o,u as s,d as t,B as _,a as f,h}from"./app.b53879ed.js";import{_ as b}from"./InputError.78c0204c.js";import{_ as x}from"./BackButton.b8d687e3.js";import{s as y}from"./inputtext.esm.b0b1c258.js";import{s as z}from"./button.esm.3c05891c.js";import{s as j}from"./toast.esm.607cb02c.js";import"./portal.esm.e4d18dea.js";const w={class:"max-w-7xl mx-auto my-14 bg-white p-8 rounded-xl shadow shadow-slate-300"},v=t("h1",null,"Edytuj projekt",-1),V=["onSubmit"],k={class:"flex flex-col mt-4"},B=t("label",{class:"mb-2 font-semibold"},"Nazwa projektu",-1),E={class:"flex justify-between mt-2"},N=h(" Zapisz formularz "),C={__name:"Edit",props:{project:Object},setup(i){const a=i,r=d(),e=c({name:a.project.name}),l=()=>{e.put(route("admin.project.update",a.project),{onStart:()=>{e.clearErrors()},onSuccess:()=>{r.add({severity:"success",summary:"Powodzenie",detail:"Formularz zapisany",life:3e3}),e.reset()},onError:()=>r.add({severity:"error",summary:"B\u0142\u0105d",detail:"Wyst\u0105pi\u0142 b\u0142\u0105d podczas zapisu formularza",life:3e3})})};return(S,m)=>(p(),u("div",w,[o(s(j)),t("div",null,[v,t("form",{onSubmit:_(l,["prevent"])},[t("div",k,[B,o(s(y),{type:"text",modelValue:s(e).name,"onUpdate:modelValue":m[0]||(m[0]=n=>s(e).name=n),placeholder:"Wpisz nazw\u0119 projektu"},null,8,["modelValue"]),o(b,{class:"mt-2",message:s(e).errors.name},null,8,["message"])]),t("div",E,[o(x),o(s(z),{label:"Primary",type:"submit"},{default:f(()=>[N]),_:1})])],40,V)])]))}};export{C as default};