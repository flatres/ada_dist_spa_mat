
#region Using Directives

// In-Built assemblies

using System;
using System.Collections.Generic;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

// iSAMS assemblies

using iSAMS.Security;
using iSAMS.Services;
using iSAMS.Entities;
using ComponentArt.Web.UI;

// 3rd Party assemblies

#endregion Using Directives

public partial class custommodules_utilities_main_all_sanilist : SecurePage
{

    #region Global Variables

    bool bStopProcessing = false;

    #endregion Global Variables

    #region Page Events

    /// <summary>
    /// PreInit
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>
    protected void Page_PreInit(object sender, EventArgs e)
    {
        MSM = new iSAMS.Security.ModuleSecurityManager("iSAMS_CUSTOMUTILITIES", Global.SystemVars.SYS_UserSecurity);
    }

    /// <summary>
    /// Init
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>

    string start;
    string finish;

    protected void Page_Init(object sender, EventArgs e)
    {
        infCriticalError.ImageDir = Global.DynamicVars.txtImageDir + "16/";

        if (!bStopProcessing)
            RegisterClientScripts();

        if (!bStopProcessing)
            WireUpControls();

        if (!bStopProcessing)
            BindData();

        if (!bStopProcessing)
            ApplyStyling();

        display(sender, e);

    }

    /// <summary>
    /// Load
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>


    #endregion Page Events

    #region UI Generation

    /// <summary>
    /// This method contains all client script registration
    /// </summary>
    private void RegisterClientScripts()
    {

    }

    /// <summary>
    /// This method wires up control events and properties
    /// </summary>
    private void WireUpControls()
    {
    }

    /// <summary>
    /// This method retrieves any data from the database and generates the appropriate UI content
    /// </summary>
    private void BindData()
    {

        PupilManagementPupilsService pupilsService = new PupilManagementPupilsService();
        TList<PupilManagementPupils> pupilsList = pupilsService.GetBySystemStatus(PupilManagementPupils.SystemStatus.Current);

        foreach (PupilManagementPupils pupil in pupilsList)
        {
            //Response.Write(pupil.TxtForename + "<br/>");
        }

        pupilsList.Dispose();
    }

    /// <summary>
    /// This method applies styling attributes to page elements and controls
    /// </summary>
    private void ApplyStyling()
    {
    }

    #endregion UI Generation

    #region Methods

    #endregion Methods
    protected void display(object sender, EventArgs e)
    {
        string sql = "SELECT p.txtSurname + ', ' + p.txtForename as name, p.txtBoardingHouse, ";
        sql = sql + "CASE p.intNCYear WHEN 13 THEN 'U' WHEN 12 THEN 'L' WHEN 11 THEN 'H' ";
        sql = sql + "WHEN 10 THEN 'R' WHEN 9 THEN 'S' END AS yg, ";
        sql = sql + "CASE s.txtEntryType ";
        sql = sql + "WHEN 'home' THEN 'At Home' ";
        sql = sql + "WHEN 'sani' THEN 'In patient' ";
        sql = sql + "WHEN 'ld' THEN 'Lying down' ";
        sql = sql + "WHEN 'house' THEN 'Lying down in house' ";
        sql = sql + "WHEN 'osa' THEN 'Off site appointment' ";
        sql = sql + "END as place, ";
        sql = sql + "RIGHT(txtSchoolCode, 4) as numb ";
        sql = sql + "FROM TblPupilManagementPupils p, mccustom.dbo.TblSani s ";
        sql = sql + "WHERE p.txtSchoolID=s.txtSchoolID ";
        sql = sql + "AND s.txtEntryType IN ('home', 'sani', 'house', 'ld', 'osa') ";
        sql = sql + "AND s.intDeleted = 0 ";
        sql = sql + "AND p.intSystemStatus = 1 ";
        sql = sql + "ORDER BY " + ddlorder.SelectedValue + ";";
        dsPupils.SelectCommand = sql;
        rpPupils.DataBind();

    }


    #region Control Events

    #endregion Control Events


    #region Server-Side Validation

    #endregion Server-Side Validation

    #region Error Handling

    /// <summary>
    /// Displays a critical error to the user
    /// </summary>
    /// <param name="message">Error message</param>
    private void DoCriticalError(string message)
    {
        DoCriticalError(message, true);
    }

    /// <summary>
    /// Displays a critical error to the user
    /// </summary>
    /// <param name="message">Error message</param>
    /// <param name="_bStopProcessing">Boolean to stop the processing</param>
    private void DoCriticalError(string message, bool stopProcessing)
    {
        infCriticalError.ContentText = message;
        tblCriticalError.Visible = true;
        tblMain.Visible = false;

        bStopProcessing = stopProcessing;
    }

    #endregion Error Handling

}
