<?php
namespace Topxia\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Topxia\Common\ArrayToolkit;
use Topxia\Common\Paginator;

class MemberController extends BaseController
{
    public function indexAction(Request $request)
    {	

        $currentUser = $this->getCurrentUser();
    	$conditions = array();
        $members = $this->getMemberService()->searchMembers($conditions, array('createdTime', 'DESC'), 0, 10);
        $memberIds = ArrayToolkit::column($members,'userId');
        $latestMembers = $this->getUserService()->findUsersByIds($memberIds);
    	$levels = $this->getLevelService()->searchLevels($conditions,0,100);
    	$courses = $this->getCourseService()->findCoursesByHaveUserLevelIds(0, 100);
        $member = $this->getMemberService()->getMemberByUserId($currentUser['id']);
        return $this->render('TopxiaWebBundle:Member:index.html.twig',array(
        	'levels' => $levels,
            'courses' => $courses,
            'latestMembers' => $latestMembers,
            'members'=>$members,
            'member'=>$member
        ));
    }

    public function courseAction(Request $request)
    {   
        $conditions = array();

      /*  $paginator = new Paginator(
            $request,
            $this->getCourseService()->findCoursesByUserLevelIdCount($id),
            15
        );
*/
     /*   $courses = $this->getCourseService()->findCoursesByUserLevelId(
            $id,                
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );*/

        
        $levels = $this->getLevelService()->searchLevels($conditions,0,100);
        return $this->render('TopxiaWebBundle:Member:course.html.twig',array(
            'levels' => $levels
        ));
    }

    public function historyAction(Request $request)
    {       
        $conditions = array();
        $levels = $this->getLevelService()->searchLevels($conditions,0,100);
        return $this->render('TopxiaWebBundle:Member:history.html.twig', array(
            'levels' => $levels
        ));
    }

    protected function getUserService()
    {
        return $this->getServiceKernel()->createService('User.UserService');
    }

    protected function getLevelService()
    {
    	return $this->getServiceKernel()->createService('User.LevelService');
    }

    protected function getCourseService()
    {
    	return $this->getServiceKernel()->createService('Course.CourseService');
    }

    protected function getMemberService()
    {
        return $this->getServiceKernel()->createService('User.MemberService');
    }
}