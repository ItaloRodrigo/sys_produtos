import axios from "axios";
// import JSZip from "jszip";

function api(ctx = null, download = false) {
  let config = {
    baseURL: import.meta.env.VITE_API,
    headers: {
      "Accept-Language": (
        // localStorage.getItem("lang") || i18n.global.locale
        import.meta.env.VITE_I18N_LOCALE 
      ),
      // "Access-Control-Allow-Origin": "*",
      // "Access-Control-Allow-Headers": "Origin, X-Requested-With, Content-Type, Accept",
      // 'Access-Control-Allow-Methods': 'POST, GET, PUT, DELETE, OPTIONS',
      "Accept": "application/json"
    },
  };
  if (ctx != null) {
    if ("$auth" in ctx) config.headers["Authorization"] = "Bearer " + ctx.$auth.user.token;
    // if ("current_token" in ctx) config.headers["Authorization"] = "Token " + ctx.current_token;
  }
  if (download) {
    config["responseType"] = 'blob';
  }
  return axios.create(config);
}
function except(ctx, erro, erroIgnore = null) {
  if ("response" in erro && "$router" in ctx) {
    if (erro.response.status == 401 && erro.config.url != "login") {
      ctx.$router.push("/logout");
    } else if (erro.response.status == erroIgnore) {
      if ("form_errors" in ctx && erro.response.status == 400) {
        for (let key in ctx.form_errors) ctx.form_errors[key].length = 0;
        ctx.form_errors = Object.assign(
          ctx.form_errors,
          erro.response.data.fields
        );
      }
    } else {
      let params = {
        pathMatch: ctx.$route.path.substring(1).split("/"),
        code: erro.response.status,
        code_name: erro.code,
        api: erro.request.responseURL || "",
      };
      if (erro.response.data != undefined) {
        params["message"] =
          erro.response.data.detail || erro.response.statusText;
        params["class_api"] = erro.response.data.class || "";
        params["error"] = erro.response.data.error || "";
        params["fields"] = JSON.stringify(erro.response.data.fields || {});
      } else {
        params["message"] = erro.response.statusText;
      }
      ctx.$router.push({
        name: "error",
        params: params,
      });
      return true;
    }
  } else console.warn(erro);
  return false;
}
function saveFile(response, title) {
  const url = window.URL.createObjectURL(new Blob([response.data]))
  const link = document.createElement('a')
  link.href = url
  link.setAttribute('download', title)
  document.body.appendChild(link)
  link.click()
}
function saveFileMulti(response) {
  // JSZip.loadAsync(response.data).then((zip) => {
  //   // console.log('zip',zip);
  //   Object.keys(zip.files).forEach(async (filename) => {
  //     // console.log('filename',filename,await zip.files[filename].async('blob'));
  //     const url = window.URL.createObjectURL(new Blob([await zip.files[filename].async('blob')]))
  //     const link = document.createElement('a')
  //     link.href = url
  //     link.setAttribute('download', filename)
  //     document.body.appendChild(link)
  //     link.click()
  //   });
  // });
}
export default api;
export { api, except, saveFile,saveFileMulti };
