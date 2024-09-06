<?php
use PHPMailer\PHPMailer\PHPMailer;

session_start();

// connect to database
$db = mysqli_connect('mysql833.umbler.com:41890', 'dpopinterativo', ',u-(Cw8Eh8', 'dpopinterativo');

mysqli_query($db, "SET NAMES 'utf8'");
mysqli_query($db, 'SET character_set_connection=utf8');
mysqli_query($db, 'SET character_set_client=utf8');
mysqli_query($db, 'SET character_set_results=utf8');

$qs = explode("?", $_SERVER['REQUEST_URI']);
$urlPage = basename($qs[0], ".php");
$pageList = [
    "lista-usuarios" => "Lista de Usuários"
    ,
    "auth.forgot-password" => "Esqueci minha senha"
    ,
    "auth.sign-in" => "Login"
    ,
    "index" => "Página Inicial"
    ,
    "perfil" => "Meu Perfil"
    ,
    "template" => "Templates"
    ,
    "template-list" => "Lista de Templates"
    ,
    "template-content" => "Templates - Conteúdo"
    ,
    "template-content-home" => "Templates - Conteúdo"
    ,
    "template-content-buttons" => "Templates - Conteúdo"
    ,
    "template-content-text" => "Templates - Conteúdo"
    ,
    "template-content-product-line" => "Templates - Conteúdo"
    ,
    "template-content-step-by-step" => "Templates - Conteúdo"
    ,
    "template-content-home-feature" => "Templates - Conteúdo"
    ,
    "template-content-list" => "Lista de Templates - Conteúdo"
    ,
    "operator-template" => "Operadoras - Templates"
    ,
    "operator-template-list" => "Operadoras - Templates"
    ,
    "device" => "Aparelhos"
    ,
    "device-list" => "Aparelhos"
    ,
    "operator-plan" => "Operadoras - Planos"
    ,
    "operator-plan-list" => "Operadoras - Planos"
    ,
    "operator-value" => "Operadoras - Valores"
    ,
    "operator-value-list" => "Operadoras - Valores"
    ,
    "operator-user" => "Operadoras - Usuários"
    ,
    "operator-user-list" => "Operadoras - Usuários"
    ,
    "update-site" => "Atualizar - Site"
    ,
    "navigation-control" => "Registro de Navegação"
];
$siteName = 'Samsung - Pop Interativo';
$siteDomain = 'dpopinterativo.dev.br';
$minCouponValue = 400;
$_SESSION['errors'] = [];

function e($val)
{
    global $db;
    return mysqli_real_escape_string($db, trim($val));
}

function isLoggedIn()
{
    if (isset($_SESSION['user'])) {
        return true;
    } else {
        return false;
    }
}

function display_error()
{
    if (isset($_SESSION['errors'])) {
        $errors = $_SESSION['errors'];
        $_SESSION['errors'] = array();

        if (count($errors) > 0) {
            echo '<div class="error text-center text-muted red"><label class="error">';
            foreach ($errors as $error) {
                echo $error . '<br>';
            }
            echo '</label></div>';
        }
    }
}

function displayPersonLoginError()
{
    if (isset($_SESSION['personLoginError'])) {
        $errors = $_SESSION['personLoginError'];
        $_SESSION['personLoginError'] = array();

        if (count($errors) > 0) {
            echo '<div class="row mb-5 mt-5 error"><div class="col-md-12">';
            foreach ($errors as $error) {
                echo $error . '<br>';
            }
            echo '</div></div>';
        }
    }
}

function loginAdmin()
{
    global $db;

    // grap form values
    $userEmail = e($_POST['userEmail']);
    $userPassword = e($_POST['userPassword']);

    // if (count($_SESSION['errors']) == 0) {
    $query = "
        SELECT
            usr.UserID      AS UserID
            , usr.Name      AS UserName
            , usr.Email     AS Email
            , usr.LastLogin
            , ust.UserTypeID
            , ust.Name AS UserType
        FROM
            User usr
            INNER JOIN UserType ust ON usr.UserTypeID = ust.UserTypeID
        WHERE
            usr.Email = '$userEmail' AND usr.Password = '$userPassword' AND usr.IsDeleted = 0 AND usr.IsActive = 1
        LIMIT 1";
    $results = mysqli_query($db, $query);
    if (mysqli_num_rows($results) == 1) {
        $logged_in_user = mysqli_fetch_assoc($results);
        $_SESSION['user'] = $logged_in_user;
        userUpdateLastLogin($_SESSION['user']['UserID']);
        header('location: index.php');
    } else {
        array_push($_SESSION['errors'], "E-mail ou senha inválido.");
        header('location: auth.sign-in.php');
    }
    // }
}

function sendPassword()
{
    global $db, $siteName, $siteDomain;

    // grap form values
    $userEmail = e($_POST['userEmail']);

    if (count($_SESSION['errors']) == 0) {
        $query = "
        SELECT
            usr.UserID      AS UserID
            , usr.Name      AS UserName
            , usr.Email     AS Email
            , usr.Password  AS Password
        FROM
            User usr
        WHERE
            usr.Email = '$userEmail' AND usr.IsDeleted = 0
        LIMIT 1";
        $results = mysqli_query($db, $query);
        if (mysqli_num_rows($results) == 1) {
            $result = mysqli_fetch_assoc($results);

            $name = $result['UserName'];
            $email = $result['Email'];
            $password = $result['Password'];
            $subject = "$siteName - Senha de acesso";
            $message = "Caro(a) $name,\n\nSua senha de acesso é $password.";

            $headers = "From: site@$siteDomain\r\n";
            $headers .= "Answer-Type: text/html; charset=UTF-8\r\n";
            $headers .= "Reply-To: $email\r\n";

            $email_to = $email;

            $status = mail($email_to, $subject, $message, $headers);

            if ($status) {
                array_push($_SESSION['errors'], "A senha foi enviada para o E-Mail informado.");
            } else {
                array_push(
                    $_SESSION['errors'],
                    "Não foi possível enviar o E-Mail com sua senha no momento. Tente novamente mais tarde."
                );
            }
        } else {
            array_push($_SESSION['errors'], "O E-mail informado não existe em nossa base de usuários.");
        }
    }
}

function userTypeList()
{
    global $db;
    $query = "
    SELECT
        ust.UserTypeID
        , ust.Name AS UserType
    FROM
        UserType ust
    WHERE
        ust.IsActive = 1
        AND ust.IsDeleted = 0";
    return mysqli_query($db, $query);
}

function userList()
{
    global $db;
    $query = "
    SELECT
        usr.UserID      AS UserID
        , usr.Name      AS UserName
        , usr.Email     AS Email
        , DATE_FORMAT(usr.LastLogin, '%d/%m/%Y %H:%i') AS LastLogin
        , ust.UserTypeID
        , ust.Name AS UserType
    FROM
        User usr
        INNER JOIN UserType ust ON usr.UserTypeID = ust.UserTypeID
    WHERE
        usr.IsActive = 1
        AND usr.IsDeleted = 0";
    return mysqli_query($db, $query);
}

function userListByType($userTypeID)
{
    global $db;
    $query = "
    SELECT
        usr.UserID      AS UserID
        , usr.Name      AS UserName
        , usr.Email     AS Email
        , ust.UserTypeID
        , ust.UserType
    FROM
        User usr
        INNER JOIN UserType ust ON usr.UserTypeID = ust.UserTypeID
    WHERE
        usr.IsActive = 1
        AND usr.IsDeleted = 0
        AND ust.UserTypeID = '$userTypeID'";
    return mysqli_query($db, $query);
}

function userGet($id)
{
    global $db;
    $query = "
    SELECT
        usr.UserID      AS UserID
        , usr.Name      AS UserName
        , usr.Email     AS Email
        , usr.Password  AS Password
        , ust.UserTypeID
        , ust.Name AS UserType
    FROM
        User usr
        INNER JOIN UserType ust ON usr.UserTypeID = ust.UserTypeID
    WHERE
        usr.UserID = $id";
    return mysqli_query($db, $query);
}

function userInsert($userTypeID, $userName, $userEmail, $userPassword, $applicationList)
{
    global $db;
    $query = "
        INSERT INTO User (UserTypeID, Name, Email, Password, IsActive, DControl)
        VALUES ('$userTypeID', '$userName', '$userEmail', '$userPassword', 1, NOW())
    ";
    mysqli_query($db, $query);
    $userID = mysqli_insert_id($db);

    userApplicationInsert($userID, $applicationList);
}

function userUpdate($userID, $userTypeID, $userName, $userEmail, $userPassword, $applicationList)
{
    global $db;
    $query = "
    UPDATE User SET
        UserTypeID = '$userTypeID'
        , Name = '$userName'
        , Email = '$userEmail'
        , Password = '$userPassword'
        , DControl = NOW()
    WHERE
        UserID = $userID";
    mysqli_query($db, $query);

    userApplicationInsert($userID, $applicationList);
}

function userUpdateLastLogin($userID)
{
    global $db;
    $query = "
    UPDATE User SET
        LastLogin = NOW()
    WHERE
        UserID = $userID";
    mysqli_query($db, $query);
}

function userDelete($userID)
{
    global $db;
    $query = "
    UPDATE User SET
        IsActive = 0
        , IsDeleted = 1
        , DControl = NOW()
    WHERE
        UserID = $userID";
    return mysqli_query($db, $query);
}

function userApplicationByPersonID($userID, $applicationID)
{
    global $db;
    $query = "
    SELECT
        upk.ApplicationID
    FROM
        UserApplication upk
    WHERE
        upk.UserID = '$userID'
        AND upk.ApplicationID = '$applicationID'";
    $results = mysqli_query($db, $query);
    if (mysqli_num_rows($results) == 1) {
        $result = mysqli_fetch_assoc($results);
        return $result['ApplicationID'];
    }

    return 0;
}

function userApplicationInsert($userID, $applicationList)
{
    global $db;
    $query = "DELETE FROM UserApplication WHERE UserID = $userID";
    mysqli_query($db, $query);

    foreach ($applicationList as $applicationID) {
        $query = "INSERT INTO UserApplication (UserID, ApplicationID) VALUES ('$userID', '$applicationID')
    ";
        mysqli_query($db, $query);
    }
}

function applicationList()
{
    global $db;
    $query = "
    SELECT
        apk.ApplicationID
        , apk.OperatorID
        , apk.Application
        , apk.ApplicationSlug
    FROM
        Application apk
    WHERE
        apk.IsActive = 1
        AND apk.IsDeleted = 0";
    return mysqli_query($db, $query);
}

function applicationByUserList($userID, $isOperator = "")
{
    global $db;
    $query = "
    SELECT
        apk.ApplicationID
        , apk.Application
    FROM
        Application apk
        INNER JOIN UserApplication upk ON apk.ApplicationID = upk.ApplicationID
    WHERE
        apk.IsActive = 1
        AND apk.IsDeleted = 0
        AND upk.UserID = $userID ";

    if ($isOperator != "") {
        $query .= " AND OperatorID " . ($isOperator == 1 ? " <> 0" : " = 0") . "";
    }

    return mysqli_query($db, $query);
}

function pageTypeByUserList($userID, $applicationID = null)
{
    global $db;
    $query = "
    SELECT
        pgt.PageTypeID
        , pgt.ApplicationID
        , pgt.PageType
    FROM
        PageType pgt
        INNER JOIN UserApplication upk ON pgt.ApplicationID = upk.ApplicationID
    WHERE
        pgt.IsActive = 1
        AND upk.UserID = $userID
        " . (isset($applicationID) && $applicationID != null ? " AND pgt.ApplicationID = $applicationID " : "") . "
        AND pgt.IsDeleted = 0";
    return mysqli_query($db, $query);
}

function templateGet($templateID)
{
    global $db;
    $query = "
    SELECT
        tmp.TemplateID
        , apk.ApplicationID
        , apk.Application
        , tmp.PageTypeID
        , pgt.PageType
        , tmp.Template
        , tmp.Logo
        , tmp.ButtonContent
        , tmp.HeaderText
        , tmp.SpecificationLine1
        , tmp.SpecificationLine2
        , tmp.FootNote
        , tmp.Color
        , IFNULL(tmp.IsHeaderColorWhite, 0) AS IsHeaderColorWhite
        , tmp.IsMainPage
        , tmp.IsActive
        , tmp.IsDeleted
    FROM
        Template tmp
        INNER JOIN Application apk ON apk.ApplicationID = tmp.ApplicationID
        INNER JOIN PageType pgt ON pgt.PageTypeID = tmp.PageTypeID
    WHERE
        tmp.TemplateID = $templateID";
    return mysqli_query($db, $query);
}

function templateList($isOperatorExclusive = 0, $applicationID = null)
{
    global $db;
    $query = "
    SELECT
        tmp.TemplateID
        , apk.ApplicationID
        , apk.Application
        , tmp.PageTypeID
        , pgt.PageType
        , tmp.Template
        , tmp.Logo
        , tmp.ButtonContent
        , tmp.HeaderText
        , tmp.SpecificationLine1
        , tmp.SpecificationLine2
        , tmp.FootNote
        , tmp.Color
        , IFNULL(tmp.IsHeaderColorWhite, 0) AS IsHeaderColorWhite
        , tmp.IsMainPage
        , tmp.IsActive
        , tmp.IsDeleted
    FROM
        Template tmp
        INNER JOIN Application apk ON apk.ApplicationID = tmp.ApplicationID
        INNER JOIN PageType pgt ON pgt.PageTypeID = tmp.PageTypeID
    WHERE
        tmp.IsDeleted = 0
        AND tmp.IsOperatorExclusive = $isOperatorExclusive
        " . (isset($applicationID) && $applicationID != null ? " AND tmp.ApplicationID = $applicationID " : "") . "";
    return mysqli_query($db, $query);
}

function templateByUserList($userID, $isOperatorExclusive, $applicationID = null)
{
    global $db;
    $query = "
    SELECT
        tmp.TemplateID
        , apk.ApplicationID
        , apk.Application
        , tmp.PageTypeID
        , pgt.PageType
        , tmp.Template
        , tmp.Logo
        , tmp.ButtonContent
        , tmp.HeaderText
        , tmp.SpecificationLine1
        , tmp.SpecificationLine2
        , tmp.FootNote
        , tmp.Color
        , IFNULL(tmp.IsHeaderColorWhite, 0) AS IsHeaderColorWhite
        , tmp.IsMainPage
        , tmp.IsActive
        , tmp.IsDeleted
    FROM
        Template tmp
        INNER JOIN Application apk ON apk.ApplicationID = tmp.ApplicationID
        INNER JOIN UserApplication upk ON apk.ApplicationID = upk.ApplicationID
        INNER JOIN PageType pgt ON pgt.PageTypeID = tmp.PageTypeID
    WHERE
        tmp.IsDeleted = 0
        AND tmp.IsOperatorExclusive = $isOperatorExclusive
        AND upk.UserID = $userID
        " . (isset($applicationID) && $applicationID != null ? " AND tmp.ApplicationID = $applicationID " : "") . "";
    return mysqli_query($db, $query);
}

function templateInsert($applicationID, $pageTypeID, $template, $logo, $buttonContent, $headerText, $specificationLine1, $specificationLine2, $footNote, $color, $isHeaderColorWhite, $isMainPage, $isOperatorExclusive, $isActive)
{
    global $db;

    $query = "
        INSERT INTO Template (ApplicationID, PageTypeID, Template, Logo, ButtonContent, HeaderText, SpecificationLine1, SpecificationLine2, FootNote, Color, IsHeaderColorWhite, IsMainPage, IsOperatorExclusive, IsActive, IsDeleted, DControl)
        VALUES ('$applicationID', '$pageTypeID', '$template', '$logo', '$buttonContent', '$headerText', '$specificationLine1', '$specificationLine2', '$footNote', '$color', $isHeaderColorWhite, $isMainPage, $isOperatorExclusive, $isActive, 0, NOW())
    ";
    $result = mysqli_query($db, $query);

    if ($result) {
        $templateID = mysqli_insert_id($db);
        templateUpdateMainPage($templateID, $isOperatorExclusive);
    }

    generateUserOperatorFile();
}

function templateUpdate($templateID, $applicationID, $pageTypeID, $template, $logo, $buttonContent, $headerText, $specificationLine1, $specificationLine2, $footNote, $color, $isHeaderColorWhite, $isMainPage, $isOperatorExclusive, $isActive)
{
    global $db;
    $query = "
    UPDATE Template SET
        ApplicationID = '$applicationID'
        , PageTypeID = '$pageTypeID'
        , Template = '$template'
        , Logo = '$logo'
        , ButtonContent = '$buttonContent'
        , HeaderText = '$headerText'
        , SpecificationLine1 = '$specificationLine1'
        , SpecificationLine2 = '$specificationLine2'
        , FootNote = '$footNote'
        , Color = '$color'
        , IsHeaderColorWhite = $isHeaderColorWhite
        , IsMainPage = $isMainPage
        , IsOperatorExclusive = $isOperatorExclusive
        , IsActive = $isActive
        , DControl = NOW()
    WHERE
        TemplateID = $templateID";
    mysqli_query($db, $query);

    templateUpdateMainPage($templateID, $isOperatorExclusive);

    generateUserOperatorFile();
}

function templateUpdateMainPage($templateID, $isOperatorExclusive)
{
    return;

    // global $db;
    // $query = "
    // UPDATE Template SET IsMainPage = 0 WHERE TemplateID <> $templateID";

    // if ($isOperatorExclusive == '0')
    //     return mysqli_query($db, $query);
}

function templateDelete($templateID)
{
    global $db;
    $query = "
    UPDATE Template SET
        IsActive = 0
        , IsDeleted = 1
        , DControl = NOW()
    WHERE
        TemplateID = $templateID";
    return mysqli_query($db, $query);
}

function buttonSizeList()
{
    global $db;
    $query = "
    SELECT
        btz.ButtonSizeID
        , btz.ButtonSize
        , btz.Size
        , btz.IsActive
    FROM
        ButtonSize btz
    WHERE
        btz.IsDeleted = 0";
    return mysqli_query($db, $query);
}

function buttonPositionList()
{
    global $db;
    $query = "
    SELECT
        btp.ButtonPositionID
        , btp.ButtonPosition
        , btp.ButtonPositionFlag
        , btp.IsActive
    FROM
        ButtonPosition btp
    WHERE
        btp.IsDeleted = 0";
    return mysqli_query($db, $query);
}

function contentOrientationList()
{
    global $db;
    $query = "
    SELECT
        cto.ContentOrientationID
        , cto.ContentOrientation
        , cto.Flag
        , cto.IsActive
    FROM
        ContentOrientation cto
    WHERE
        cto.IsDeleted = 0";
    return mysqli_query($db, $query);
}

function templateContentList()
{
    global $db;
    $query = "
    SELECT
        tpc.TemplateContentID
        , apk.ApplicationID
        , apk.Application
        , pgt.PageTypeID
        , pgt.PageType
        , tmp.TemplateID
        , tmp.Template
        , tpc.ButtonSizeID
        , btz.ButtonSize
        , tpc.ButtonPositionID
        , btp.ButtonPosition
        , btp.ButtonPositionFlag
        , tpc.ContentOrientationID
        , cto.ContentOrientation
        , tpc.TemplateContentChildID
        , tpc.TemplateContent
        , tpc.Title
        , tpc.SubTitle
        , tpc.Content
        , tpc.Footnote
        , tpc.ButtonOrder
        , tpc.Media
        , tpc.CoverImage
        , tpc.PositionTop
        , tpc.PositionLeft
        , tpc.TextTitleColor
        , tpc.TextColor
        , tpc.Style
        , IFNULL(tpc.IsWhiteTitle, 0) AS IsWhiteTitle
        , IFNULL(tpc.IsTextRight, 0) AS IsTextRight
        , tpc.IsActive
    FROM
        TemplateContent tpc
        INNER JOIN Application apk ON tpc.ApplicationID = apk.ApplicationID
        INNER JOIN Template tmp ON tpc.TemplateID = tmp.TemplateID
        INNER JOIN PageType pgt ON pgt.PageTypeID = tmp.PageTypeID
        LEFT JOIN ButtonSize btz ON tpc.ButtonSizeID = btz.ButtonSizeID
        LEFT JOIN ButtonPosition btp ON tpc.ButtonPositionID = btp.ButtonPositionID
        LEFT JOIN TemplateContent tcc ON tpc.TemplateContentID = tcc.TemplateContentChildID
        LEFT JOIN ContentOrientation cto ON tpc.ContentOrientationID = cto.ContentOrientationID
    WHERE
        tpc.IsDeleted = 0
    ORDER BY
        apk.Application
        , tmp.Template
        , tpc.TemplateContent";
    return mysqli_query($db, $query);
}

function templateContentListByPageTypeID($pageTypeID)
{
    global $db;
    $query = "
    SELECT
        tpc.TemplateContentID
        , apk.ApplicationID
        , apk.Application
        , pgt.PageTypeID
        , pgt.PageType
        , tmp.TemplateID
        , tmp.Template
        , tpc.ButtonSizeID
        , btz.ButtonSize
        , tpc.ButtonPositionID
        , btp.ButtonPosition
        , btp.ButtonPositionFlag
        , tpc.ContentOrientationID
        , cto.ContentOrientation
        , tpc.TemplateContentChildID
        , tpc.TemplateContent
        , tpc.Title
        , tpc.SubTitle
        , tpc.Content
        , tpc.Footnote
        , tpc.ButtonOrder
        , tpc.Media
        , tpc.CoverImage
        , tpc.PositionTop
        , tpc.PositionLeft
        , tpc.TextTitleColor
        , tpc.TextColor
        , tpc.Style
        , IFNULL(tpc.IsWhiteTitle, 0) AS IsWhiteTitle
        , IFNULL(tpc.IsTextRight, 0) AS IsTextRight
        , tpc.IsActive
    FROM
        TemplateContent tpc
        INNER JOIN Application apk ON tpc.ApplicationID = apk.ApplicationID
        LEFT JOIN Template tmp ON tpc.TemplateID = tmp.TemplateID
        LEFT JOIN PageType pgt ON pgt.PageTypeID = tmp.PageTypeID
        LEFT JOIN ButtonSize btz ON tpc.ButtonSizeID = btz.ButtonSizeID
        LEFT JOIN ButtonPosition btp ON tpc.ButtonPositionID = btp.ButtonPositionID
        LEFT JOIN TemplateContent tcc ON tpc.TemplateContentID = tcc.TemplateContentChildID
        LEFT JOIN ContentOrientation cto ON tpc.ContentOrientationID = cto.ContentOrientationID
    WHERE
        tpc.IsDeleted = 0
        AND (tmp.PageTypeID IN($pageTypeID)";

    if ($pageTypeID == "0,2,6") {
        $query .= " OR tpc.TemplateID = 0";
    }

    $query .= ")";
    return mysqli_query($db, $query);
}

function templateContentListFiles()
{
    global $db;
    $query = "
    SELECT
        tpc.Media
    FROM
        TemplateContent tpc
    WHERE
        tpc.IsActive = 1
        AND tpc.IsDeleted = 0
    UNION SELECT
        tpc.CoverImage AS Media
    FROM
        TemplateContent tpc
    WHERE
        tpc.IsActive = 1
        AND tpc.IsDeleted = 0";
    return mysqli_query($db, $query);
}

function operatorTemplateListFiles()
{
    global $db;
    $query = "
    SELECT
        tpl.Logo
    FROM
        Template tpl
    WHERE
        tpl.IsActive = 1
        AND tpl.IsDeleted = 0
        ANd tpl.IsOperatorExclusive = 1";
    return mysqli_query($db, $query);
}

function operatorValueListFiles()
{
    global $db;
    $query = "
    SELECT
        opv.Media
    FROM
        OperatorValue opv
    WHERE
        opv.IsActive = 1
        AND opv.IsDeleted = 0";
    return mysqli_query($db, $query);
}

function templateContentGet($templateContentID)
{
    global $db;
    $query = "
    SELECT
        tpc.TemplateContentID
        , apk.ApplicationID
        , apk.Application
        , pgt.PageTypeID
        , pgt.PageType
        , tmp.TemplateID
        , tmp.Template
        , IF(tpc.ButtonSizeID <> '0', tpc.ButtonSizeID, '') AS ButtonSizeID
        , btz.ButtonSize
        , IF(tpc.ButtonPositionID <> '0', tpc.ButtonPositionID, '') AS ButtonPositionID
        , btp.ButtonPosition
        , btp.ButtonPositionFlag
        , IF(tpc.ContentOrientationID <> '0', tpc.ContentOrientationID, '') AS ContentOrientationID
        , cto.ContentOrientation
        , IF(tpc.TemplateChildID <> '0', tpc.TemplateChildID, '') AS TemplateChildID
        , IF(tpc.TemplateContentChildID <> '0', tpc.TemplateContentChildID, '') AS TemplateContentChildID
        , tpc.TemplateContent
        , tpc.Title
        , tpc.SubTitle
        , tpc.Content
        , tpc.Footnote
        , tpc.ButtonOrder
        , tpc.Media
        , tpc.CoverImage
        , tpc.PositionTop
        , tpc.PositionLeft
        , tpc.TextTitleColor
        , tpc.TextColor
        , tpc.Style
        , IFNULL(tpc.IsWhiteTitle, 0) AS IsWhiteTitle
        , IFNULL(tpc.IsTextRight, 0) AS IsTextRight
        , tpc.IsActive
    FROM
        TemplateContent tpc
        INNER JOIN Application apk ON tpc.ApplicationID = apk.ApplicationID
        LEFT JOIN Template tmp ON tpc.TemplateID = tmp.TemplateID
        LEFT JOIN PageType pgt ON pgt.PageTypeID = tmp.PageTypeID
        LEFT JOIN ButtonSize btz ON tpc.ButtonSizeID = btz.ButtonSizeID
        LEFT JOIN ButtonPosition btp ON tpc.ButtonPositionID = btp.ButtonPositionID
        LEFT JOIN TemplateContent tcc ON tpc.TemplateContentID = tcc.TemplateContentChildID
        LEFT JOIN ContentOrientation cto ON tpc.ContentOrientationID = cto.ContentOrientationID
    WHERE
        tpc.TemplateContentID = $templateContentID";
    return mysqli_query($db, $query);
}

function templateContentInsert($applicationID, $templateID, $buttonSizeID, $buttonPositionID, $contentOrientationID, $templateChildID, $templateContentChildID, $templateContent, $title, $subTitle, $content, $footnote, $buttonOrder, $media, $coverImage, $positionTop, $positionLeft, $textTitleColor, $textColor, $style, $isWhiteTitle, $isTextRight, $isActive)
{
    global $db;
    $query = "
        INSERT INTO TemplateContent (ApplicationID, TemplateID, ButtonSizeID, ButtonPositionID, ContentOrientationID, TemplateChildID, TemplateContentChildID, TemplateContent, Title, SubTitle, Content, Footnote, ButtonOrder, Media, CoverImage, PositionTop, PositionLeft, TextTitleColor, TextColor, Style, IsWhiteTitle, IsTextRight, IsActive, IsDeleted, DControl)
        VALUES ('$applicationID', '$templateID', '$buttonSizeID', '$buttonPositionID', '$contentOrientationID', '$templateChildID', '$templateContentChildID', '$templateContent', '$title', '$subTitle', '$content', '$footnote', '$buttonOrder', '$media', '$coverImage', '$positionTop', '$positionLeft', '$textTitleColor', '$textColor', '$style', $isWhiteTitle, $isTextRight, '$isActive', 0, NOW())
    ";
    mysqli_query($db, $query);
}

function templateContentUpdate($templateContentID, $applicationID, $templateID, $buttonSizeID, $buttonPositionID, $contentOrientationID, $templateChildID, $templateContentChildID, $templateContent, $title, $subTitle, $content, $footnote, $buttonOrder, $media, $coverImage, $positionTop, $positionLeft, $textTitleColor, $textColor, $style, $isWhiteTitle, $isTextRight, $isActive)
{
    global $db;
    $query = "
    UPDATE TemplateContent SET
        ApplicationID = '$applicationID'
        , TemplateID = '$templateID'
        , ButtonSizeID = '$buttonSizeID'
        , ButtonPositionID = '$buttonPositionID'
        , ContentOrientationID = '$contentOrientationID'
        , TemplateChildID = '$templateChildID'
        , TemplateContentChildID = '$templateContentChildID'
        , TemplateContent = '$templateContent'
        , Title = '$title'
        , SubTitle = '$subTitle'
        , Content = '$content'
        , Footnote = '$footnote'
        , ButtonOrder = '$buttonOrder'
        , Media = '$media'
        , CoverImage = '$coverImage'
        , PositionTop = '$positionTop'
        , PositionLeft = '$positionLeft'
        , TextTitleColor = '$textTitleColor'
        , TextColor = '$textColor'
        , Style = '$style'
        , IsWhiteTitle = $isWhiteTitle
        , IsTextRight = $isTextRight
        , IsActive = $isActive
        , DControl = NOW()
    WHERE
        TemplateContentID = $templateContentID";
    mysqli_query($db, $query);
}

function templateContentDelete($templateContentID)
{
    global $db;
    $query = "
    UPDATE TemplateContent SET
        IsActive = 0
        , IsDeleted = 1
        , DControl = NOW()
    WHERE
        TemplateContentID = $templateContentID";
    return mysqli_query($db, $query);
}

function deviceList()
{
    global $db;
    $query = "
    SELECT
        dvc.DeviceID
        , dvc.Device
    FROM
        Device dvc
    WHERE
        dvc.IsActive = 1
        AND dvc.IsDeleted = 0";
    return mysqli_query($db, $query);
}

function deviceGet($id)
{
    global $db;
    $query = "
    SELECT
        dvc.DeviceID
        , dvc.Device
        , dvc.IsActive
    FROM
        Device dvc
    WHERE
        dvc.DeviceID = $id";
    return mysqli_query($db, $query);
}

function deviceInsert($device, $isActive)
{
    global $db;
    $query = "
        INSERT INTO Device (Device, IsActive, DControl)
        VALUES ('$device', $isActive, NOW())
    ";
    return mysqli_query($db, $query);
}

function deviceUpdate($deviceID, $device, $isActive)
{
    global $db;
    $query = "
    UPDATE Device SET
        Device = '$device'
        , IsActive = '$isActive'
        , DControl = NOW()
    WHERE
        DeviceID = $deviceID";
    return mysqli_query($db, $query);
}

function deviceDelete($deviceID)
{
    global $db;
    $query = "
    UPDATE Device SET
        IsActive = 0
        , IsDeleted = 1
        , DControl = NOW()
    WHERE
        DeviceID = $deviceID";
    return mysqli_query($db, $query);
}

function operatorPlanGet($operatorPlanID)
{
    global $db;
    $query = "
    SELECT
        opl.OperatorPlanID
        , apk.ApplicationID
        , apk.Application
        , opl.OperatorPlan
        , opl.ButtonContent
        , opl.FootNote
        , opl.PlanOrder
        , opl.IsActive
        , opl.IsDeleted
    FROM
        OperatorPlan opl
        INNER JOIN Application apk ON apk.ApplicationID = opl.ApplicationID
    WHERE
        opl.OperatorPlanID = $operatorPlanID";
    return mysqli_query($db, $query);
}

function operatorPlanList($applicationID = null)
{
    global $db;
    $query = "
    SELECT
        opl.OperatorPlanID
        , apk.ApplicationID
        , apk.Application
        , opl.OperatorPlan
        , opl.ButtonContent
        , opl.FootNote
        , opl.PlanOrder
        , opl.IsActive
        , opl.IsDeleted
    FROM
        OperatorPlan opl
        INNER JOIN Application apk ON apk.ApplicationID = opl.ApplicationID
    WHERE
        opl.IsDeleted = 0
        " . (isset($applicationID) && $applicationID != null ? " AND opl.ApplicationID = $applicationID " : "") . "";
    return mysqli_query($db, $query);
}

function operatorPlanByUserList($userID)
{
    global $db;
    $query = "
    SELECT
        opl.OperatorPlanID
        , apk.ApplicationID
        , apk.Application
        , opl.OperatorPlan
        , opl.ButtonContent
        , opl.FootNote
        , opl.PlanOrder
        , opl.IsActive
        , opl.IsDeleted
    FROM
        OperatorPlan opl
        INNER JOIN Application apk ON apk.ApplicationID = opl.ApplicationID
        INNER JOIN UserApplication upk ON apk.ApplicationID = upk.ApplicationID
    WHERE
        opl.IsDeleted = 0
        AND upk.UserID = $userID";
    return mysqli_query($db, $query);
}

function operatorPlanInsert($applicationID, $operatorPlan, $buttonContent, $footNote, $planOrder, $isActive)
{
    global $db;
    $query = "
        INSERT INTO OperatorPlan (ApplicationID, OperatorPlan, ButtonContent, FootNote, PlanOrder, IsActive, IsDeleted, DControl)
        VALUES ('$applicationID', '$operatorPlan', '$buttonContent', '$footNote', $planOrder, $isActive, 0, NOW())
    ";
    mysqli_query($db, $query);

    generateUserOperatorFile();
}

function operatorPlanUpdate($operatorPlanID, $applicationID, $operatorPlan, $buttonContent, $footNote, $planOrder, $isActive)
{
    global $db;
    $query = "
    UPDATE OperatorPlan SET
        ApplicationID = '$applicationID'
        , OperatorPlan = '$operatorPlan'
        , ButtonContent = '$buttonContent'
        , FootNote = '$footNote'
        , PlanOrder = '$planOrder'
        , IsActive = '$isActive'
        , DControl = NOW()
    WHERE
        OperatorPlanID = $operatorPlanID";
    mysqli_query($db, $query);

    generateUserOperatorFile();
}

function operatorPlanDelete($operatorPlanID)
{
    global $db;
    $query = "
    UPDATE OperatorPlan SET
        IsActive = 0
        , IsDeleted = 1
        , DControl = NOW()
    WHERE
        OperatorPlanID = $operatorPlanID";
    return mysqli_query($db, $query);
}

function operatorValueGet($operatorValueID)
{
    global $db;
    $query = "
    SELECT
        opv.OperatorValueID
        , apk.ApplicationID
        , apk.Application
        , opu.OperatorUserID
        , opu.OperatorUser
        , tpl.TemplateID
        , tpl.Template
        , opl.OperatorPlanID
        , opl.OperatorPlan
        , dvc.DeviceID
        , dvc.Device
        , opv.PlanDetail
        , REPLACE(FORMAT(opv.DeviceValue, 2), ',', '') AS DeviceValue
        , REPLACE(FORMAT(opv.PlanMonthValue, 2), ',', '') AS PlanMonthValue
        , opv.Installments
        , opv.Media
        , opv.Observation
        , opv.IsActive
        , opv.IsDeleted
    FROM
        OperatorValue opv
        INNER JOIN Application apk ON apk.ApplicationID = opv.ApplicationID
        INNER JOIN OperatorUser opu ON opv.OperatorUserID = opu.OperatorUserID
        INNER JOIN Template tpl ON tpl.TemplateID = opv.TemplateID
        INNER JOIN OperatorPlan opl ON opl.OperatorPlanID = opv.OperatorPlanID
        INNER JOIN Device dvc ON dvc.DeviceID = opv.DeviceID
    WHERE
        opv.OperatorValueID = $operatorValueID";
    return mysqli_query($db, $query);
}

function operatorValueList($applicationID = null)
{
    global $db;
    $query = "
    SELECT
        opv.OperatorValueID
        , apk.ApplicationID
        , apk.Application
        , opu.OperatorUserID
        , opu.OperatorUser
        , tpl.TemplateID
        , tpl.Template
        , opl.OperatorPlanID
        , opl.OperatorPlan
        , dvc.DeviceID
        , dvc.Device
        , opv.PlanDetail
        , REPLACE(FORMAT(opv.DeviceValue, 2), ',', '') AS DeviceValue
        , REPLACE(FORMAT(opv.PlanMonthValue, 2), ',', '') AS PlanMonthValue
        , opv.Installments
        , opv.Media
        , opv.Observation
        , opv.IsActive
        , opv.IsDeleted
    FROM
        OperatorValue opv
        INNER JOIN Application apk ON apk.ApplicationID = opv.ApplicationID
        INNER JOIN OperatorUser opu ON opv.OperatorUserID = opu.OperatorUserID
        INNER JOIN Template tpl ON tpl.TemplateID = opv.TemplateID
        INNER JOIN OperatorPlan opl ON opl.OperatorPlanID = opv.OperatorPlanID
        INNER JOIN Device dvc ON dvc.DeviceID = opv.DeviceID
    WHERE
        opv.IsDeleted = 0
        " . (isset($applicationID) && $applicationID != null ? " AND opv.ApplicationID = $applicationID " : "") . "";
    return mysqli_query($db, $query);
}

function operatorValueByUserList($userID)
{
    global $db;
    $query = "
    SELECT
        opv.OperatorValueID
        , apk.ApplicationID
        , apk.Application
        , opu.OperatorUserID
        , opu.OperatorUser
        , tpl.TemplateID
        , tpl.Template
        , opl.OperatorPlanID
        , opl.OperatorPlan
        , dvc.DeviceID
        , dvc.Device
        , opv.PlanDetail
        , REPLACE(FORMAT(opv.DeviceValue, 2), ',', '') AS DeviceValue
        , REPLACE(FORMAT(opv.PlanMonthValue, 2), ',', '') AS PlanMonthValue
        , opv.Installments
        , opv.Media
        , opv.Observation
        , opv.IsActive
        , opv.IsDeleted
    FROM
        OperatorValue opv
        INNER JOIN Application apk ON apk.ApplicationID = opv.ApplicationID
        INNER JOIN OperatorUser opu ON opv.OperatorUserID = opu.OperatorUserID
        INNER JOIN Template tpl ON tpl.TemplateID = opv.TemplateID
        INNER JOIN OperatorPlan opl ON opl.OperatorPlanID = opv.OperatorPlanID
        INNER JOIN Device dvc ON dvc.DeviceID = opv.DeviceID
        INNER JOIN UserApplication upk ON apk.ApplicationID = upk.ApplicationID
    WHERE
        opv.IsDeleted = 0
        AND upk.UserID = $userID";
    return mysqli_query($db, $query);
}

function operatorValueInsert($applicationID, $operatorUserID, $templateID, $operatorPlanID, $deviceID, $planDetail, $deviceValue, $planMonthValue, $installments, $media, $observation, $isActive)
{
    global $db;
    $query = "
        INSERT INTO OperatorValue (ApplicationID, OperatorUserID, TemplateID, OperatorPlanID, DeviceID, PlanDetail, DeviceValue, PlanMonthValue, Installments, Media, Observation, IsActive, IsDeleted, DControl)
        VALUES ('$applicationID', '$operatorUserID', '$templateID', '$operatorPlanID', '$deviceID', '$planDetail', '$deviceValue', '$planMonthValue', '$installments', '$media', '$observation', $isActive, 0, NOW())
    ";
    mysqli_query($db, $query);

    generateUserOperatorFile();
}

function operatorValueUpdate($operatorValueID, $applicationID, $operatorUserID, $templateID, $operatorPlanID, $deviceID, $planDetail, $deviceValue, $planMonthValue, $installments, $media, $observation, $isActive)
{
    global $db;
    $query = "
    UPDATE OperatorValue SET
        ApplicationID = '$applicationID'
        , OperatorUserID = '$operatorUserID'
        , TemplateID = '$templateID'
        , OperatorPlanID = '$operatorPlanID'
        , DeviceID = '$deviceID'
        , PlanDetail = '$planDetail'
        , DeviceValue = '$deviceValue'
        , PlanMonthValue = '$planMonthValue'
        , Installments = '$installments'
        , Media = '$media'
        , Observation = '$observation'
        , IsActive = '$isActive'
        , DControl = NOW()
    WHERE
        OperatorValueID = $operatorValueID";
    mysqli_query($db, $query);

    generateUserOperatorFile();
}

function operatorValueDelete($operatorValueID)
{
    global $db;
    $query = "
    UPDATE OperatorValue SET
        IsActive = 0
        , IsDeleted = 1
        , DControl = NOW()
    WHERE
        OperatorValueID = $operatorValueID";
    return mysqli_query($db, $query);
}

function operatorUserGet($operatorUserID)
{
    global $db;
    $query = "
    SELECT
        opu.OperatorUserID
        , apk.ApplicationID
        , apk.Application
        , opu.OperatorUser
        , opu.Login
        , opu.Password
        , opu.IsActive
        , opu.IsDeleted
    FROM
        OperatorUser opu
        INNER JOIN Application apk ON apk.ApplicationID = opu.ApplicationID
    WHERE
        opu.OperatorUserID = $operatorUserID";
    return mysqli_query($db, $query);
}

function operatorUserList()
{
    global $db;
    $query = "
    SELECT
        opu.OperatorUserID
        , apk.ApplicationID
        , apk.Application
        , apk.ApplicationSlug
        , apk.NeedLogin
        , opu.OperatorUser
        , opu.Login
        , opu.Password
        , opu.IsActive
        , opu.IsDeleted
    FROM
        OperatorUser opu
        INNER JOIN Application apk ON apk.ApplicationID = opu.ApplicationID
    WHERE
        opu.IsDeleted = 0";
    return mysqli_query($db, $query);
}

function operatorUserByUserList($userID, $isOperator = "")
{
    global $db;
    $query = "
    SELECT
        opu.OperatorUserID
        , apk.ApplicationID
        , apk.Application
        , opu.OperatorUser
        , opu.Login
        , opu.Password
        , opu.IsActive
        , opu.IsDeleted
    FROM
        OperatorUser opu
        INNER JOIN Application apk ON apk.ApplicationID = opu.ApplicationID
        INNER JOIN UserApplication upk ON apk.ApplicationID = upk.ApplicationID
    WHERE
        opu.IsDeleted = 0
        AND upk.UserID = $userID";

    if ($isOperator != "") {
        $query .= " AND apk.OperatorID " . ($isOperator == 1 ? " <> 0" : " = 0") . "";
    }

    return mysqli_query($db, $query);
}

function operatorUserInsert($applicationID, $operatorUser, $login, $password, $isActive)
{
    global $db;

    $password = hashPassword($password);

    $query = "
        INSERT INTO OperatorUser (ApplicationID, OperatorUser, Login, Password, IsActive, IsDeleted, DControl)
        VALUES ('$applicationID', '$operatorUser', '$login', '$password', $isActive, 0, NOW())
    ";
    mysqli_query($db, $query);

    generateUserJsonFile('user');
}

function operatorUserUpdate($operatorUserID, $applicationID, $operatorUser, $login, $password, $isActive)
{
    global $db;

    $query = "
    UPDATE OperatorUser SET
        ApplicationID = '$applicationID'
        , OperatorUser = '$operatorUser'
        , Login = '$login'
        , IsActive = '$isActive'
        , DControl = NOW()
    WHERE
        OperatorUserID = $operatorUserID";
    mysqli_query($db, $query);

    if ($password != '') {
        $password = hashPassword($password);

        $query = "UPDATE OperatorUser SET Password = '$password' WHERE OperatorUserID = $operatorUserID";
        mysqli_query($db, $query);
    }

    generateUserJsonFile('user');
}

function operatorUserDelete($operatorUserID)
{
    global $db;
    $query = "
    UPDATE OperatorUser SET
        IsActive = 0
        , IsDeleted = 1
        , DControl = NOW()
    WHERE
        OperatorUserID = $operatorUserID";
    return mysqli_query($db, $query);
}

function generateUserJsonFile($fileName)
{
    $userList = array();
    $result = operatorUserList();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $userList[] = $row;
        }
    }

    $json_data = json_encode($userList, JSON_PRETTY_PRINT);

    if (isset($json_data)) {
        $ds = DIRECTORY_SEPARATOR;
        $filePath = dirname(__FILE__) . $ds . '..' . $ds . '..' . $ds . 'version' . $ds . $fileName . '.json';
        file_put_contents($filePath, $json_data);
    }
}

function generateUserOperatorFile()
{
    $resultApplication = applicationList();
    if ($resultApplication->num_rows > 0) {
        while ($r = $resultApplication->fetch_assoc()) {

            if ($r['OperatorID'] != '0') {
                $fileName = $r['ApplicationSlug'];

                $template = array();
                $result = templateList(1, $r['ApplicationID']);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $template[] = $row;
                    }
                }

                $operatorPlan = array();
                $result = operatorPlanList($r['ApplicationID']);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $operatorPlan[] = $row;
                    }
                }

                $operatorValue = array();
                $result = operatorValueList($r['ApplicationID']);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $operatorValue[] = $row;
                    }
                }

                $data = array(
                    "template" => $template,
                    "operatorPlan" => $operatorPlan,
                    "operatorValue" => $operatorValue
                );

                $json_data = json_encode($data, JSON_PRETTY_PRINT);

                if (isset($json_data)) {
                    $ds = DIRECTORY_SEPARATOR;
                    $filePath = dirname(__FILE__) . $ds . '..' . $ds . '..' . $ds . 'version' . $ds . $fileName . '.json';
                    file_put_contents($filePath, $json_data);
                }
            }
        }

        generateOperatorZIPFile('operator');
    }
}

function generateJsonFile($fileName)
{
    global $db;
    $query = "
    SELECT
        tmp.TemplateID
        , apk.ApplicationID
        , apk.Application
        , apk.ApplicationSlug
        , pgt.PageTypeID
        , pgt.PageType
        , tmp.Template
        , tmp.ButtonContent
        , tmp.HeaderText
        , tmp.Color
        , IFNULL(tmp.IsHeaderColorWhite, 0) AS IsHeaderColorWhite
    FROM
        Template tmp
        INNER JOIN Application apk ON tmp.ApplicationID = apk.ApplicationID
        INNER JOIN PageType pgt ON pgt.PageTypeID = tmp.PageTypeID
    WHERE
        tmp.IsActive = 1
        AND tmp.IsDeleted = 0
    ";
    $result = mysqli_query($db, $query);
    if ($result->num_rows > 0) {
        $template = array();
        while ($row = $result->fetch_assoc()) {
            $template[] = $row;
        }
    }

    $query = "
    SELECT
        tpc.TemplateContentID
        , apk.ApplicationID
        , apk.Application
        , apk.ApplicationSlug
        , pgt.PageTypeID
        , pgt.PageType
        , tmp.TemplateID
        , tmp.Template
        , tmp.ButtonContent
        , tmp.HeaderText
        , tmp.Color
        , IFNULL(tmp.IsHeaderColorWhite, 0) AS IsHeaderColorWhite
        , IF(tpc.ButtonSizeID <> '0', tpc.ButtonSizeID, '') AS ButtonSizeID
        , btz.ButtonSize
        , IF(tpc.ButtonPositionID <> '0', tpc.ButtonPositionID, '') AS ButtonPositionID
        , btp.ButtonPosition
        , btp.ButtonPositionFlag
        , IF(tpc.ContentOrientationID <> '0', tpc.ContentOrientationID, '') AS ContentOrientationID
        , cto.ContentOrientation
        , IF(tpc.TemplateChildID <> '0', tpc.TemplateChildID, '') AS TemplateChildID
        , IF(tpc.TemplateContentChildID <> '0', tpc.TemplateContentChildID, '') AS TemplateContentChildID
        , tpc.TemplateContent
        , tpc.Title
        , tpc.SubTitle
        , tpc.Content
        , tpc.Footnote
        , tpc.ButtonOrder
        , tpc.Media
        , tpc.CoverImage
        , tpc.PositionTop
        , tpc.PositionLeft
        , tpc.TextTitleColor
        , tpc.TextColor
        , tpc.Style
        , IFNULL(tpc.IsWhiteTitle, 0) AS IsWhiteTitle
        , IFNULL(tpc.IsTextRight, 0) AS IsTextRight
        , tpc.IsActive
        , IFNULL(tmp.IsMainPage, 0) AS IsMainPage
    FROM
        TemplateContent tpc
        INNER JOIN Application apk ON tpc.ApplicationID = apk.ApplicationID
        LEFT JOIN Template tmp ON tpc.TemplateID = tmp.TemplateID
        LEFT JOIN PageType pgt ON pgt.PageTypeID = tmp.PageTypeID
        LEFT JOIN ButtonSize btz ON tpc.ButtonSizeID = btz.ButtonSizeID
        LEFT JOIN ButtonPosition btp ON tpc.ButtonPositionID = btp.ButtonPositionID
        LEFT JOIN TemplateContent tcc ON tpc.TemplateContentID = tcc.TemplateContentChildID
        LEFT JOIN ContentOrientation cto ON tpc.ContentOrientationID = cto.ContentOrientationID
    WHERE
        tpc.IsActive = 1
        AND tpc.IsDeleted = 0
    ORDER BY
        tpc.ButtonOrder
    ";
    $result = mysqli_query($db, $query);
    if ($result->num_rows > 0) {
        $templateContent = array();
        while ($row = $result->fetch_assoc()) {
            $templateContent[] = $row;
        }
    }

    $data = array(
        "template" => $template,
        "templateContent" => $templateContent
    );

    $json_data = json_encode($data, JSON_PRETTY_PRINT);

    if (isset($json_data)) {
        $ds = DIRECTORY_SEPARATOR;
        $filePath = dirname(__FILE__) . $ds . '..' . $ds . '..' . $ds . 'version' . $ds . $fileName . '.json';
        file_put_contents($filePath, $json_data);
    }
}

function tabletVersionList()
{
    global $db;
    $query = "
    SELECT
        TabletVersion
        , IsProduction
        , DATE_FORMAT(DControl, '%d/%m/%Y %H:%i:%s') AS DControl
    FROM
        TabletVersion
    ORDER BY
        DControl DESC
    ";
    return mysqli_query($db, $query);
}

function insertTabletVersion($guid)
{
    global $db;

    $query = "UPDATE TabletVersion SET IsProduction = 0";
    mysqli_query($db, $query);

    $query = "INSERT INTO TabletVersion (TabletVersion, IsProduction, DControl) VALUES ('$guid', 1, NOW())";
    mysqli_query($db, $query);

    generateTabletVersionFile($guid);
}

function insertTabletVersionDetail($guid, $deviceID, $deviceModel, $deviceManufacturer)
{
    global $db;

    $query = "UPDATE TabletVersionDetail SET IsPublished = 0 WHERE DeviceID = '$deviceID'";
    mysqli_query($db, $query);

    $query = "INSERT INTO TabletVersionDetail (TabletVersion, DeviceID, DeviceModel, DeviceManufacturer, IsPublished, DStart, DFinished) 
    VALUES ('$guid', '$deviceID', '$deviceModel', '$deviceManufacturer', 1, NOW(), NOW())";
    mysqli_query($db, $query);
}

function insertNavigationControl($guid, $actionID, $templateID, $templateContentID, $actionDate, $deviceID, $deviceModel, $deviceManufacturer)
{
    global $db;
    $query = "INSERT INTO NavigationControl (ActionID, TemplateID, TemplateContentID, ActionDate, TabletVersion, DeviceID, DeviceModel, DeviceManufacturer) 
    VALUES ('$actionID', '$templateID', '$templateContentID', '$actionDate', '$guid', '$deviceID', '$deviceModel', '$deviceManufacturer')";
    mysqli_query($db, $query);
}

function updateTabletVersion($guid, $isProduction)
{
    global $db;

    $query = "UPDATE TabletVersion SET IsProduction = 0";
    mysqli_query($db, $query);

    $query = "UPDATE TabletVersion SET IsProduction = $isProduction WHERE TabletVersion = '$guid'";
    mysqli_query($db, $query);

    generateTabletVersionFile($guid);
}

function generateTabletVersionFile($guid)
{
    $version = (object) [
        'version' => $guid
    ];
    $json_data = json_encode($version, JSON_PRETTY_PRINT);
    if (isset($json_data)) {
        $ds = DIRECTORY_SEPARATOR;
        $filePath = dirname(__FILE__) . $ds . '..' . $ds . '..' . $ds . 'version' . $ds . 'version.json';
        file_put_contents($filePath, $json_data);
    }
    generateZIPFile($guid);
}

function generateGUID()
{
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 4
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function getTabletVersionDetail($guid)
{
    global $db;
    $query = "
    SELECT
        COUNT(TabletVersion) AS Quantity
    FROM
        TabletVersionDetail
    WHERE
        TabletVersion = '$guid'
        AND IsPublished = 1
    GROUP BY
        TabletVersion
    ";
    $results = mysqli_query($db, $query);
    if (mysqli_num_rows($results) == 1) {
        $row = mysqli_fetch_assoc($results);
        return $row["Quantity"];
    }
    return '0';
}

function generateZIPFile($guid)
{
    $zip = new ZipArchive();
    $ds = DIRECTORY_SEPARATOR;
    $zipFileName = dirname(__FILE__) . $ds . '..' . $ds . '..' . $ds . 'version' . $ds . $guid . '.zip';

    if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
        $result = templateContentListFiles();
        while ($row = mysqli_fetch_array($result)) {
            if ($row['Media'] != '') {
                $file = dirname(__FILE__) . $ds . '..' . $ds . '..' . $ds . 'content' . $ds . $row['Media'];
                if (file_exists($file)) {
                    $zip->addFile($file, $row['Media']);
                }
            }
        }

        $zip->close();
    }
}

function generateOperatorZIPFile($fileName)
{
    $zip = new ZipArchive();
    $ds = DIRECTORY_SEPARATOR;
    $zipFileName = dirname(__FILE__) . $ds . '..' . $ds . '..' . $ds . 'version' . $ds . $fileName . '.zip';

    if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
        $result = operatorTemplateListFiles();
        while ($row = mysqli_fetch_array($result)) {
            if ($row['Logo'] != '') {
                $file = dirname(__FILE__) . $ds . '..' . $ds . '..' . $ds . 'content' . $ds . $row['Logo'];
                $zip->addFile($file, $row['Logo']);
            }
        }

        $result = operatorValueListFiles();
        while ($row = mysqli_fetch_array($result)) {
            if ($row['Media'] != '') {
                $file = dirname(__FILE__) . $ds . '..' . $ds . '..' . $ds . 'content' . $ds . $row['Media'];
                $zip->addFile($file, $row['Media']);
            }
        }

        $zip->close();
    }
}

function navigationControlList()
{
    global $db;
    $query = "
    SELECT
        nvc.NavigationControlID
        , act.ActionID
        , act.Action
        , tmp.TemplateID
        , tmp.Template
        , pgt.PageTypeID
        , pgt.PageType
        , tpc.TemplateContentID
        , tpc.TemplateContent
        , apk.ApplicationID
        , apk.Application
        , DATE_FORMAT(nvc.ActionDate, '%d/%m/%Y %H:%i:%s') AS ActionDate
        , nvc.TabletVersion
        , nvc.DeviceID
        , nvc.DeviceModel
        , nvc.DeviceManufacturer
        , tbv.IsProduction
    FROM
        NavigationControl nvc
        INNER JOIN Action act ON nvc.ActionID = act.ActionID
        INNER JOIN Template tmp ON nvc.TemplateID = tmp.TemplateID
        INNER JOIN PageType pgt ON tmp.PageTypeID = pgt.PageTypeID
        INNER JOIN Application apk ON tmp.ApplicationID = apk.ApplicationID
        INNER JOIN TabletVersion tbv ON nvc.TabletVersion = tbv.TabletVersion
        LEFT JOIN TemplateContent tpc ON nvc.TemplateContentID = tpc.TemplateContentID
    ORDER BY
        nvc.ActionDate DESC
    ";
    return mysqli_query($db, $query);
}

function hashPassword($password)
{
    $hashedPassword = hash('sha256', $password);
    return base64_encode($hashedPassword);
}
?>