using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Services;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Xml;

namespace WebApplication1 {
	public partial class _Default : Page {
		protected void Page_Load(object sender, EventArgs e) {
			//ProcessData();
			//ProcessDataJson();
		}

		public class CoOrdinate {
			public decimal Lattitude { get; set; }
			public decimal Longitude { get; set; }
		}

		[WebMethod]
		public void ProcessData() {

			string value = HttpContext.Current.Request.QueryString["latlngs"];
			if (value != null) {
				string[] array = value.Split(']');
				List<CoOrdinate> CoOrdinates = new List<CoOrdinate>();
				foreach (string str in array) {
					if (!String.IsNullOrEmpty(str)) {
						string coOrdinates = str.Remove(0, 1);
						string[] mapsValue = coOrdinates.Split(',');
						CoOrdinate coOrdinate = new CoOrdinate();
						coOrdinate.Lattitude = Convert.ToDecimal(mapsValue[0]);
						coOrdinate.Longitude = Convert.ToDecimal(mapsValue[1]);
						CoOrdinates.Add(coOrdinate);

					}

				}

				//call sp

			}
		}

		[WebMethod]
		public void ProcessDataJson() {
			dynamic mapCoOrdinates = HttpContext.Current.Request.Form["root"];
			//string mapCoOrdinates = HttpContext.Current.Request.QueryString["latlngs"];
			
			if (!string.IsNullOrEmpty(mapCoOrdinates)) {
				XmlDocument document = (XmlDocument)JsonConvert.DeserializeXmlNode(JsonConvert.SerializeObject(mapCoOrdinates));
				//XmlDocument document = (XmlDocument)JsonConvert.DeserializeXmlNode(mapCoOrdinates);
			}
		}
	}
}