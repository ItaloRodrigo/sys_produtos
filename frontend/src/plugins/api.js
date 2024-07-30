import axios from "axios";

function api(ctx = null, download = false, isFileUpload = false) {
  let config = {
    baseURL: import.meta.env.VITE_API,
    headers: {
      "Accept-Language": (
        // localStorage.getItem("lang") || i18n.global.locale
        import.meta.env.VITE_I18N_LOCALE
      ),
      "Accept": "application/json"
    },
  };

  if (ctx != null) {
    if ("$auth" in ctx) config.headers["Authorization"] = "Bearer " + ctx.$auth.token;
  }

  if (download) {
    config["responseType"] = 'blob';
  }

  if (isFileUpload) {
    config.headers["Content-Type"] = "multipart/form-data";
  }

  return axios.create(config);
}
function except(ctx, erro, erroIgnore = null) {
  if ("response" in erro && "$router" in ctx) {
    const { status, data } = erro.response;
    const { url } = erro.config;

    if (status === 401) {
      // Redireciona para a página de login ou realiza logout
      if (url !== "/login") {
        ctx.$router.push("/logout");
      }
    } else if (status === erroIgnore) {
      if ("form_errors" in ctx && status === 400) {
        for (let key in ctx.form_errors) ctx.form_errors[key].length = 0;
        ctx.form_errors = Object.assign(ctx.form_errors, data.fields);
      }
    } else {
      let params = {
        pathMatch: ctx.$route.path.substring(1).split("/"),
        code: status,
        code_name: erro.code,
        api: erro.request.responseURL || "",
        message: data.detail || erro.response.statusText,
        class_api: data.class || "",
        error: data.error || "",
        fields: JSON.stringify(data.fields || {})
      };

      ctx.$router.push({
        name: "error",
        params: params,
      });
      return true;
    }
  } else {
    console.warn(erro);
  }
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
